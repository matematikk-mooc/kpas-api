<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class GroupEnrollmentService
{
    protected $guzzleClient;
    protected $statistics_base_url;

    public function __construct()
    {
        $this->statistics_base_url = config('statistics-api.base_url');
    }

    public function getGroupEnrollment(int $courseId, string $fromDate, string $toDate) {
        logger("GroupEnrollment::getGroupEnrollment");
        $url = "{$this->statistics_base_url}/statistics/${courseId}?from={$fromDate}&to={$toDate}&format=json";
        logger($url);

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, [], [], false);
        return $res;
    }


    protected function request(string $courseId, string $fromDate, string $toDate, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {   
        $fullUrl = "{$this->statistics_base_url}/statistics/course/{$courseId}?from={$fromDate}&to={$toDate}";
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

            logger("GroupEnrollmentService: returning content");

            return $content;
        } catch (ClientException $exception) {
            logger("GroupEnrollmentService::request exception:");
            throw $exception;
        }
    }



}