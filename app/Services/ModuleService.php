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
        $result =  $this->guzzleClient->request('GET', $url);
        return $result;

    }

    public function getModuleStatisticsByGroup(int $courseId, $groupId)
    {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/modules?group={$groupId}&format=json";
        return $this->guzzleClient->request('GET', $url);
    }

    public function getModuleStatisticsCount(int $courseId)
    {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/modules/count?format=json";
        $result =  $this->guzzleClient->request('GET', $url);
        return $result;

    }
}
