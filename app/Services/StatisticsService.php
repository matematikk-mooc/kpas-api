<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class StatisticsService
{
    protected $guzzleClient;
    protected $statistics_base_url;

    public function __construct()
    {
        $this->statistics_base_url = config('statistics-api.base_url');
    }


    public function getStatisticsHtml($settings) {
        logger("getStatisticsHtml");
        logger($settings);

        $this->guzzleClient = new Client();
        $statistics = $this->request("502");

        return view('main.statistics')
        ->withStatistics($statistics)
        ->withSettings($settings);
    }

    public function getUserActivity(int $courseId, Request $request) {
        logger("StatisticsService::getUserActivity");
        $url = "{$this->statistics_base_url}/statistics/user_activity/" . $courseId . "?";

        if ($request->has('from')) {
            $url .= "from=" . $from . "&";
        }

        if ($request->has('to')) {
            $url .= "to=" . $to;
        }

        $this->guzzleClient = new Client();
        logger($url);
        return $this->guzzleClient->request($url);
    }
    
    protected function request(string $courseId, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "{$this->statistics_base_url}/statistics/{$courseId}/count";
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
