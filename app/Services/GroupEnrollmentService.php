<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class GroupEnrollmentService
{
    protected $guzzleClient;
    protected $statisticsBaseUrl;

    public function __construct()
    {
        $this->statistics_base_url = config('statistics-api.base_url');
    }

    public function getGroupEnrollment(int $courseId, string $fromDate, string $toDate)
    {
        logger("GroupEnrollment::getGroupEnrollment");
        $url = "{$this->statisticsBaseUrl}/statistics/${courseId}?from={$fromDate}&to={$toDate}&format=json";

        $this->guzzleClient = new Client();
        $res = $this->guzzleClient->request('GET', $url, []);
        return $res;
    }

    protected function request(string $courseId, string $fromDate, string $toDate, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "{$this->statisticsBaseUrl}/statistics/course/{$courseId}?from={$fromDate}&to={$toDate}";

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
            logger("GroupEnrollmentService::request exception:");
            throw $exception;
        }
    }
}
