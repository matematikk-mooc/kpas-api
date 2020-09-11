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
        return in_array($k, $school_keys);;
    }, ARRAY_FILTER_USE_KEY);
}

class DataNsrService
{
    /**
     * @var Client
     */
    protected $guzzleClient;
    protected $domain;

    public function __construct(Client $guzzleClient)
    {
        $this->domain = 'https://data-nsr.udir.no/';
        $this->guzzleClient = $guzzleClient;
    }

    public function getCounties(): array
    {
        return $this->request('fylker');
    }

    public function getCommunities(): array
    {
        return $this->request('kommuner');
    }

    public function getSchools(): array
    {
        return $this->request('enheter');
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [])
    {
        logger($url);
        $fullUrl = $this->domain . $url;
        $response = $this->guzzleClient->request($method, $fullUrl, [
            'form_params' => $data,
            'headers' => $headers,
            'verify' => false,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     *  Fetch Kindergartens from https://data-nbr.udir.no/enheter
     * @return array
     * @throws GuzzleException
     */
    protected function getKindergartens()
    {
        $fullUrl = "https://data-nbr.udir.no/enheter";
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
                $value->ErOffentligSkole ||
                $value->ErOffentligSkole) {
                $school = (array)$value;
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
            $kindergarten = (array)$value;
            try {
                $filter_fields = filter_institution_fields($kindergarten, $kindergartens_keys);
                $model->UpdateBarnehage($filter_fields);
            } catch (\Exception $e) {
                logger($e);
            }
        }
    }

}

