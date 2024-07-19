<?php

namespace App\Services;

use App\Models\Barnehage;
use App\Models\Fylke;
use App\Models\Kommune;
use App\Models\Skole;
use Exception;
use GuzzleHttp\Client;

function filter_institution_fields($data, $school_keys)
{
    return array_filter($data, function ($k) use ($school_keys) {
        return in_array($k, $school_keys);
    }, ARRAY_FILTER_USE_KEY);
}

class DataNsrService
{
    /**
     * @var Client
     */
    private $guzzleClient;
    private $nsrDomain;
    private $nbrDomain;
    private $nxrDomain;

    public function __construct(Client $guzzleClient)
    {
        $this->nsrDomain = 'https://data-nsr.udir.no/';
        $this->nbrDomain = 'https://data-nbr.udir.no/';
        $this->nxrDomain = 'https://data-nxr-fellestjeneste.udir.no/';
        $this->guzzleClient = $guzzleClient;
    }

    public function getSchoolByOrgNr($orgnr)
    {
        try{
            return $this->request($this->nsrDomain, "v3/enhet/$orgnr");
        }
        catch (Exception $e){
            throw new Exception("Could not find school with orgnr $orgnr");
        }
    }

    public function getKindergartenByOrgNr($orgnr)
    {
        try{
            return $this->request($this->nbrDomain, "v3/enhet/$orgnr");
        }
        catch (Exception $e) {
            throw new Exception("Could not find kindergarten with orgnr $orgnr");
        }
    }


    public function getCounties(): array
    {
        $today = date("Y-m-d");
        $fylker = $this->request($this->nxrDomain, 'api/v2/fylkedata?datotid='.$today);

        logger(print_r($fylker,true));
        return $fylker;
    }

    public function getCommunities(): array
    {
        $today = date("Y-m-d");
        return $this->request($this->nxrDomain, 'api/v2/kommunedata?datotid='.$today);
    }

    public function getSchools(): array
    {
        return $this->getEnheter($this->nsrDomain);
    }

    private function getKindergartens(): array
    {
        return $this->getEnheter($this->nbrDomain);
    }

    private function getEnheter(string $domain): array
    {
        $counties = Fylke::where("nedlagt", false)->pluck("Fylkesnr")->toArray();

        $enheter = [];
        foreach ($counties as $county) {
            //Skip the "Annet" county
            if ($county == '99') {
                continue;
            }
            $requestBody = [
                "Fylkenr" => $county,
                "InkluderNedlagte" => false,
                "InkluderAktive" => true,
                "InkluderSkoler" => true,
                "InkluderBarnehager" => true,
                "InkluderEiere" => false,
                "InkluderAndreEnheter" => false,
            ];

            $enheter_in_fylke = $this->postRequest($domain, "v3/enheter/sok", $requestBody);
            $enheter = array_merge($enheter, $enheter_in_fylke);
        }
        return $enheter;
    }

    private function postRequest(string $domain, string $endpoint, array $body): array
    {
        $fullUrl = $domain . $endpoint;
        try {
            $response = $this->guzzleClient->post($fullUrl, [
                'json' => $body,
                'verify' => false,
            ]);
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);
        } catch (\Throwable $e) {
            logger($e);
            return [];
        }
    }

    private function request(string $domain, string $url)
    {
        $fullUrl = $domain . $url;
        try {
            $response = $this->guzzleClient->request("GET", $fullUrl, [
                'verify' => false,
            ]);
        } catch (\Throwable $e) {
            logger($e);
        }

        return json_decode($response->getBody()->getContents());
    }

    public function store_counties()
    {
        logger("store_counties");
        $model = new Fylke();

        $model->createAnnetFylke();
        $county_keys = $model->getFillable();
        $counties = $this->getCounties();
        foreach ($counties as $value) {
            $county = (array)$value;
            try {
                if (!isset($county['FylkeskommuneOrganisasjonsnummer']) || !isset($county['StatsforvalterOrganisasjonsnummer'])) {
                    #This county is no longer active
                    continue;
                }
                #Nxr uses different field names, map them to our database column names
                $county['OrgNr'] = $county['FylkeskommuneOrganisasjonsnummer'];
                $county['OrgNrFylkesmann'] = $county['StatsforvalterOrganisasjonsnummer'];
                $county['Fylkesnr'] = $county['Fylkesnummer'];
                $county['Navn'] = $county['Fylkesnavn'];
                $filter_fields = filter_institution_fields($county, $county_keys);
                $model->updateFylke($filter_fields);
            } catch (\Throwable $e) {
                logger($e);
            }
        }
        logger("store_counties complete.");
    }

    public function store_communities()
    {
        logger("store_communities");
        $model = new Kommune();

        $model->createAnnenKommune();

        $community_keys = $model->getFillable();
        $communities = $this->getCommunities();
        foreach ($communities as $value) {
            $community = (array)$value;
            try {
                if (!isset($community['KommuneOrganisasjonsnummer'])) {
                    #This community is no longer active
                    continue;
                }
                #Nxr uses different field names, map them to our database column names
                $community['OrgNr'] = $community['KommuneOrganisasjonsnummer'];
                $community['Kommunenr'] = $community['Kommunenummer'];
                $community['Navn'] = $community['Kommunenavn'];
                $community['Fylkesnr'] = $community['Fylkesnummer'];
                $community['ErNedlagt'] = isset($community['ArvtakerKommuneDataIdListe']);
                $filter_fields = filter_institution_fields($community, $community_keys);
                $model->updateKommune($filter_fields);
            } catch (\Throwable $e) {
                logger($e);
            }
        }
        logger("store_communities complete.");
    }

    public function store_schools()
    {
        $model = new Skole();

        $model->createAnnenSkole();

        $school_keys = $model->getFillable();
        $org = $this->getSchools();

        $i = 0;
        foreach ($org as $school) {
            if ($school['ErSkole'] && $school['ErAktiv'] &&
                ($school['ErSkoleeier'] ||
                $school['ErGrunnskole'] ||
                $school['ErPrivatskole'] ||
                $school['ErOffentligSkole']))
            {
                $i++;
                if(!($i % 1000)) {
                    logger("store_schools processed " . $i);
                }
                try {
                    $school['NSRId'] = $school['Orgnr'];
                    $school['OrgNr'] = $school['Orgnr'];
                    $school['FylkeNr'] = $school['Fylkesnr'];
                    $school['FulltNavn'] = $school['Navn'];
                    $school['ErSkoleEier'] = $school['ErSkoleeier'];
                    $school['ErGrunnSkole'] = $school['ErGrunnskole'];
                    $school['ErPrivatSkole'] = $school['ErPrivatskole'];

                    $filter_fields = filter_institution_fields($school, $school_keys);

                    $model->updateSkole($filter_fields);
                } catch (\Throwable $e) {
                    logger("Failure when processing school:" . print_r($school, true));
                    logger($e);
                }
            }
        }
        logger("store_schools complete.");
    }

    public function store_kindergartens()
    {
        $model = new Barnehage();

        $model->createAnnenBarnehage();

        $kindergartens_keys = $model->getFillable();
        $org = $this->getKindergartens();

        $i = 0;
        foreach ($org as $kindergarten) {
            $i++;
            if(!($i % 1000)) {
                logger("store_kindergartens processed " . $i);
            }
            if (!$kindergarten['ErAktiv']){
                continue;
            }
            try {
                $kindergarten['NSRId'] = $kindergarten['Orgnr'];
                $kindergarten['OrgNr'] = $kindergarten['Orgnr'];
                $kindergarten['FylkeNr'] = $kindergarten['Fylkesnr'];
                $kindergarten['KommuneNr'] = $kindergarten['Kommunenr'];
                $kindergarten['FulltNavn'] = $kindergarten['Navn'];
                $kindergarten['ErBarnehageEier'] = $kindergarten['ErBarnehageeier'];


                $filter_fields = filter_institution_fields($kindergarten, $kindergartens_keys);
                $model->updateBarnehage($filter_fields);
            } catch (\Throwable $e) {
                logger("Failure when processing kindergarten:" . print_r($kindergarten, true));
                logger($e);
            }
        }
        logger("store_kindergartens complete.");
    }
}
