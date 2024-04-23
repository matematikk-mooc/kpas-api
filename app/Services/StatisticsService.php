<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class StatisticsService
{
    protected $guzzleClient;
    protected $statisticsBaseUrl;

    public function __construct()
    {
        $this->statisticsBaseUrl = config('statistics-api.base_url');
    }

    public function getStatisticsHtml($settings) {
        logger("getStatisticsHtml");
        logger($settings);

        return view('main.statistics')
        ->withSettings($settings);
    }

    public function getUserActivity(int $courseId, Request $request) {
        logger("StatisticsService::getUserActivity");

        $url = "{$this->statisticsBaseUrl}/statistics/user_activity/" . $courseId . "?";

        if ($request->has('from')) {
            $from = $request->from;
            $url .= "from=" . $from . "&";
        }

        if ($request->has('to')) {
            $to = $request->to;
            $url .= "to=" . $to;
        }

        $this->guzzleClient = new Client();
        logger($url);
        return $this->guzzleClient->request($url);
    }

    protected function request(string $courseId, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "{$this->statisticsBaseUrl}/statistics/{$courseId}/count";
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
