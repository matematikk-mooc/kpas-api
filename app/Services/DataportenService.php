<?php


namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class DataportenService
{
    /**
     * @var Client
     */
    protected $guzzleClient;
    /**
     * @var string
     */
    protected $domain;

    /** @var string|null */
    protected $accessKey;

    /**
     * @var string|null
     */
    protected $authDomain;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function getUserInfo(): \stdClass
    {
        return $this->request(config('dataporten.auth_api_url') . '/userinfo');
    }

    public function getFeideId($userInfo): string
    {
        if ($userIDSec = Arr::first($userInfo->user->userid_sec)) {
            $start = strpos($userIDSec, ":") + 1;
            return substr($userIDSec, $start);
        }
        throw new \Exception('Feide ID not found in user info!');
    }

    public function getExtraUserInfo()
    {
        return $this->request(config('dataporten.api_url') . '/userinfo/v1/userinfo');
    }

    public function getGroupsInfo()
    {
        return $this->request(config('dataporten.groups_api_url') . '/groups/me/groups');
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [])
    {
        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->accessKey,
        ], $headers);


        $response = $this->guzzleClient->request($method, $url, [
            'form_params' => $data,
            'headers' => $headers,
            'verify' => false,
        ]);

        $content = json_decode($response->getBody()->getContents());

        if (config('dataporten.debug')) {
            info(json_encode([
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'headers' => $headers,
                'response' => $content
            ], JSON_PRETTY_PRINT));
        }

        return $content;
    }

    public function setAccessKey(string $accessKey): void
    {
        $this->accessKey = $accessKey;
    }
}
