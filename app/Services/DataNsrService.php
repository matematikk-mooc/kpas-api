<?php

namespace App\Services;

use App\Models\Barnehage;
use App\Models\Fylke;
use App\Models\Kommune;
use App\Models\Skole;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

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

    public function __construct(Client $guzzleClient)
    {
        $this->nsrDomain = 'https://data-nsr.udir.no/';
        $this->nbrDomain = 'https://data-nbr.udir.no/';
        $this->guzzleClient = $guzzleClient;
    }

    public function getSchoolByOrgNr($orgnr)
    {
        try{
            return $this->request($this->nsrDomain, "v3/enhet/$orgnr");
        }
        catch (\Exception $e){
            throw new Exception("Could not find school with orgnr $orgnr");
        }
    }

    public function getKindergartenByOrgNr($orgnr)
    {
        try{
            return $this->request($this->nbrDomain, "v3/enhet/$orgnr");
        }
        catch (\Exception $e) {
            throw new Exception("Could not find kindergarten with orgnr $orgnr");
        }
    }


    public function getCounties(): array
    {
        $fylker = $this->request($this->nsrDomain, 'fylker');
        logger(print_r($fylker,true));
        return $fylker;
    }

    public function getCommunities(): array
    {
        return $this->request($this->nsrDomain, 'kommuner');
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
        // Get all enheter
        $enheter = $this->request($domain, 'enheter');
        $idToEnhet = array();
        $fylkesnummer = array();

        // Make an assosiative array of NSR id to enhet
        // and find all fylkesnummers in use
        foreach ($enheter as $enhet) {
            $idToEnhet[$enhet->NSRId] = $enhet;
            $fylkesnummer[$enhet->FylkeNr] = true;
        }

        // For each distinct fylkesnummer, fetch enheter again to also get longitude and latitude
        $i = 0;
        foreach ($fylkesnummer as $id => $ignore) {
            if (!is_numeric($id)) {
                logger("Invalid county number:" .$id);
                continue;
            }

            // Add lengde and breddegrad from fylke-call to enhet
            $inFylke = $this->request($domain, "enheter/fylke/$id");
            foreach ($inFylke as $enhetInFylke) {
                $i++;
                if(!($i % 1000)) {
                    logger("GetEnheter processed " . $i);
                }

                if (!isset($idToEnhet[$enhetInFylke->NSRId])) {
                    continue;
                }

                $enhet = $idToEnhet[$enhetInFylke->NSRId];
                $enhet->{'Lengdegrad'} = $enhetInFylke->Lengdegrad ?? '';
                $enhet->{'Breddegrad'} = $enhetInFylke->Breddegrad ?? '';
            }
        }

        return $idToEnhet;
    }

    private function request(string $domain, string $url)
    {
        logger($url);
        $fullUrl = $domain . $url;
        try {
            $response = $this->guzzleClient->request("GET", $fullUrl, [
                'verify' => false,
            ]);
        } catch (Throwable $e) {
            logger($e);
        }

        return json_decode($response->getBody()->getContents());
    }

    public function store_counties()
    {
        logger("store_counties");
        $model = new Fylke();

        $model->CreateAnnetFylke();

        $counties = $this->getCounties();
        foreach ($counties as $value) {
            $county = (array)$value;
            try {
                $model->UpdateFylke($county);
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

        $model->CreateAnnenKommune();

        $community_keys = $model->getFillable();
        $communities = $this->getCommunities();
        foreach ($communities as $value) {
            $community = (array)$value;
            try {
                $filter_fields = filter_institution_fields($community, $community_keys);
                $model->UpdateKommune($filter_fields);
            } catch (\Throwable $e) {
                logger($e);
            }
        }
        logger("store_communities complete.");
    }

    public function store_schools()
    {
        $model = new Skole();

        $model->CreateAnnenSkole();

        $school_keys = $model->getFillable();
        $org = $this->getSchools();

        $i = 0;
        foreach ($org as $value) {
            if ($value->ErSkole ||
                $value->ErSkoleEier ||
                $value->ErGrunnSkole ||
                $value->ErPrivatSkole ||
                $value->ErOffentligSkole)
            {
                $i++;
                if(!($i % 1000)) {
                    logger("store_schools processed " . $i);
                }
                $school = (array) $value;
                Arr::set($school, 'Kommunenr', $value->KommuneNr);
                try {
                    $filter_fields = filter_institution_fields($school, $school_keys);
                    $model->UpdateSkole($filter_fields);
                } catch (\Throwable $e) {
                    logger("Failure when processing school:" . print_r($value, true));
                    logger($e);
                }
            }
        }
        logger("store_schools complete.");
    }

    public function store_kindergartens()
    {
        $model = new Barnehage();

        $model->CreateAnnenBarnehage();

        $kindergartens_keys = $model->getFillable();
        $org = $this->getKindergartens();

        $i = 0;
        foreach ($org as $value) {
            $i++;
            if(!($i % 1000)) {
                logger("store_kindergartens processed " . $i);
            }
            $kindergarten = (array) $value;
            try {
                $filter_fields = filter_institution_fields($kindergarten, $kindergartens_keys);
                $model->UpdateBarnehage($filter_fields);
            } catch (\Throwable $e) {
                logger("Failure when processing kindergarten:" . print_r($value, true));
                logger($e);
            }
        }
        logger("store_kindergartens complete.");
    }
}
