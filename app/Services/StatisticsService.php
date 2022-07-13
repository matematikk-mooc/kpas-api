<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
class StatisticsService
{
    protected $guzzleClient;

    public function getStatisticsHtml($settings) {
        logger("getStatisticsHtml");
        logger($settings);

        $this->guzzleClient = new Client();
        $statistics = $this->request("Mathias");
        
        return view('main.statistics')->withStatistics($statistics)->withSettings($settings);
    }
    
    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "https://statistics-api.azurewebsites.net/api/statistics/513/count";
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

            logger("CanvasService: returning content");
            
            return $content;
        } catch (ClientException $exception) {
            logger("StatisticsService.request exception:");
            throw $exception;
        }
    }
}
