<?php

namespace App\Services;

use App\Barnehage;
use App\Fylke;
use App\Kommune;
use App\Skole;
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

    public function getCounties(): array
    {
        return $this->request($this->nsrDomain, 'fylker');
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
        foreach ($fylkesnummer as $id => $ignore) {
            if (!is_numeric($id)) {
                continue;
            }

            // Add lengde and breddegrad from fylke-call to enhet
            $inFylke = $this->request($domain, "enheter/fylke/$id");
            foreach ($inFylke as $enhetInFylke) {
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
        } catch (Exception $e) {
            logger($e);
        }

        return json_decode($response->getBody()->getContents());
    }

    public function store_counties()
    {
        $counties = $this->getCounties();
        foreach ($counties as $value) {
            $county = (array)$value;
            try {
                Fylke::updateOrCreate($county);
            } catch (\Exception $e) {
                logger($e);
            }
        }

    }

    public function store_communities()
    {
        $model = new Kommune();
        $community_keys = $model->getFillable();
        $communities = $this->getCommunities();
        foreach ($communities as $value) {
            $community = (array)$value;
            try {
                $filter_fields = filter_institution_fields($community, $community_keys);
                Kommune::updateOrCreate($filter_fields);
            } catch (\Exception $e) {
                logger($e);
            }
        }

    }

    public function store_schools()
    {
        $model = new Skole();
        $school_keys = $model->getFillable();
        $org = $this->getSchools();

        foreach ($org as $value) {
            if ($value->ErSkole ||
                $value->ErSkoleEier ||
                $value->ErGrunnSkole ||
                $value->ErPrivatSkole ||
                $value->ErOffentligSkole)
            {
                $school = (array) $value;
                Arr::set($school, 'Kommunenr', $value->KommuneNr);
                try {
                    $filter_fields = filter_institution_fields($school, $school_keys);
                    $model->UpdateSkole($filter_fields);
                } catch (\Exception $e) {
                    logger($e);
                }
            }
        }
    }

    public function store_kindergartens()
    {
        $model = new Barnehage();
        $kindergartens_keys = $model->getFillable();
        $org = $this->getKindergartens();

        foreach ($org as $value) {
            $kindergarten = (array) $value;
            try {
                $filter_fields = filter_institution_fields($kindergarten, $kindergartens_keys);
                $model->UpdateBarnehage($filter_fields);
            } catch (\Exception $e) {
                logger($e);
            }
        }
    }
}

