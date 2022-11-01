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
        $this->$statistics_base_url = config('statistics-api.base_url');
    }

    public function getModuleStatistics(int $courseId)
    {
        $url = "{$this->$statistics_base_url}/statistics/course/{$courseId}/modules";
        $this->$guzzleClient = new Client();
        return $this->$guzzleClient->request($url);
    }
}