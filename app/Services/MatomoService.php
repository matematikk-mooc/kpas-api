<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class MatomoService
{
    protected $guzzleClient;
    protected $statistics_base_url;

    public function __construct()
    {
        $this->statistics_base_url = config('statistics-api.base_url');
    }

    public function getMatomoStatistics2() {
        logger("getMatomoData");

        $this->guzzleClient = new Client();
        $matomoData = $this->request("502", "2022-10-01", "2022-10-13");
        
        return view('main.matomo')->withMatomoData($matomoData);
    }
    
    public function getMatomoStatistics(int $courseId, string $from, string $to) {
        logger("Matomo::getMatomoStatistics");
        $url = "{$this->statistics_base_url}/statistics/course/502/pages/?from={$from}&to={$to}&format=json";
        logger($url);

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, [], [], false);
        logger("Returned result: ");
        //logger($res);
        return $res;
    }
    

    protected function request(string $courseId, string $fromDate, string $toDate, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {   
        $fullUrl = "{$this->statistics_base_url}/statistics/course/{$courseId}/pages?from={$fromDate}&to={$toDate}";
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

            logger("MatomoService: returning content");
            
            return $content;
        } catch (ClientException $exception) {
            logger("MatomoService.request exception:");
            throw $exception;
        }
    }



}