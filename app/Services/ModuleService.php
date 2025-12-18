<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class ModuleService
{
    protected $guzzleClient;
    protected $statisticsBaseUrl;

    public function __construct()
    {
        $this->statisticsBaseUrl = config('statistics-api.base_url');
        $this->guzzleClient = new Client();
    }

    public function getModuleStatistics(int $courseId)
    {
        $url = "{$this->statisticsBaseUrl}/statistics/course/{$courseId}/modules?format=json";
        return $this->guzzleClient->request('GET', $url);
    }

    public function getModuleStatisticsByGroup(int $courseId, $groupId)
    {
        $url = "{$this->statisticsBaseUrl}/statistics/course/{$courseId}/modules?group={$groupId}&format=json";
        return $this->guzzleClient->request('GET', $url);
    }

    public function getModuleStatisticsCount(int $courseId)
    {
        $url = "{$this->statisticsBaseUrl}/statistics/course/{$courseId}/modules/count?format=json";
        return $this->guzzleClient->request('GET', $url);

    }

    public function getModuleStatisticsPerDate(int $moduleId)
    {
        $url = "{$this->statisticsBaseUrl}/statistics/modules/{$moduleId}/per_date";
        return $this->guzzleClient->request('GET', $url);
    }
}
