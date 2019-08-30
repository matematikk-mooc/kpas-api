<?php

namespace App\Services;

use GuzzleHttp\Client;

class DataNsrService
{
    /**
     * @var Client
     */
    protected $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->domain = 'https://data-nsr.udir.no/';
        $this->guzzleClient = $guzzleClient;
    }

    public function getCounties(): array
    {
        return $this->request('fylker');
    }

    public function getCommunities(string $countyId): array
    {
        return $this->request('kommuner/' . $countyId);
    }

    public function getSchools(string $communityId): array
    {
        return $this->request('enheter/kommune/' . $communityId);
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [])
    {
        $fullUrl = $this->domain . $url;

        $response = $this->guzzleClient->request($method, $fullUrl, [
            'form_params' => $data,
            'headers' => $headers,
            'verify' => false,
        ]);

        $content = json_decode($response->getBody()->getContents());

        return $content;
    }
}
