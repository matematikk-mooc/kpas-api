<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class HistoryService
{
    protected $guzzleClient;
    protected $statisticsBaseUrl;

    public function __construct()
    {
        $this->statisticsBaseUrl = config('statistics-api.base_url');
    }

    public function getUserHistory(int $userId, string $from, string $to)
    {
        logger("HistoryService::getUserHistory");
        $url = "{$this->statisticsBaseUrl}/statistics/user/{$userId}/history/?from={$from}&to={$to}&format=json";
        logger($url);

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, []);
        return $res;
    }

    public function getUserContextHistory(int $userId, int $contextId, string $from, string $to)
    {
        logger("HistoryService::getUserContextHistory");
        $url = "{$this->statisticsBaseUrl}/statistics/user/{$userId}/context/{$contextId}/history/?from={$from}&to={$to}&format=json";
        logger($url);

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, []);
        return $res;
    }

    public function getContextHistory(int $contextId, string $from, string $to)
    {
        logger("HistoryService::getContextHistory");
        $url = "{$this->statisticsBaseUrl}/statistics/context/{$contextId}/history/?from={$from}&to={$to}&format=json";
        logger($url);

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, []);
        return $res;
    }

    protected function request(string $userId, string $fromDate, string $toDate, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "{$this->statisticsBaseUrl}/statistics/user/{$userId}/history/?from={$fromDate}&to={$toDate}";
        logger($fullUrl);

        try {
            $content = [];
            $response = $this->guzzleClient->request($method, $fullUrl, [
                'form_params' => $data,
                'headers' => $headers,
                'verify' => false,
            ]);

            $decodedContent = json_decode($response->getBody()->getContents());
            $content = is_array($decodedContent) ? array_merge($content, $decodedContent) : $decodedContent;

            logger("HistoryService::request returning content");

            return $content;
        } catch (ClientException $exception) {
            logger("HistoryService::request exception:");
            throw $exception;
        }
    }
}
