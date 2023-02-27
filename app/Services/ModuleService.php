<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class ModuleService
{
    protected $guzzleClient;
    protected $statistics_base_url;

    public function __construct()
    {
        logger(config('statistics-api.base_url'));
        $this->statistics_base_url = config('statistics-api.base_url');
        $this->guzzleClient = new Client();
    }

    public function getModuleStatistics(int $courseId)
    {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/modules?format=json";
        logger($url);
        $result =  $this->guzzleClient->request('GET', $url);
        logger(print_r($result, true));
        return $result;

    }

    public function getModuleStatisticsByGroup(int $courseId, int $groupId)
    {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/modules?group={$groupId}&format=json";
        return $this->guzzleClient->request('GET', $url);
    }
}
