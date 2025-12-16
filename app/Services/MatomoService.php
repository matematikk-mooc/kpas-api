<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class MatomoService
{
    protected $guzzleClient;
    protected $statisticsBaseUrl;

    public function __construct()
    {
        $this->statisticsBaseUrl = config('statistics-api.base_url');
    }

    public function matomoData(int $courseid, string $from, string $to)
    {
        logger("MatomoService::getMatomoData");
        $url = "{$this->statisticsBaseUrl}/statistics/course/{$courseid}/pages?from={$from}&to={$to}&format=json";

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, []);
        return $res;
    }


    protected function request(string $courseId, string $fromDate, string $toDate, string $method = 'GET', array $data = [], array $headers = [])
    {
        $fullUrl = "{$this->statisticsBaseUrl}/statistics/course/{$courseId}/pages?from={$fromDate}&to={$toDate}";

        try {
            $content = [];
            $response = $this->guzzleClient->request($method, $fullUrl, [
                'form_params' => $data,
                'headers' => $headers,
                'verify' => false,
            ]);

            $decodedContent = json_decode($response->getBody()->getContents());
            $content = is_array($decodedContent) ? array_merge($content, $decodedContent) : $decodedContent;

            return $content;
        } catch (ClientException $exception) {
            logger("MatomoService::request exception:");
            throw $exception;
        }
    }
}
