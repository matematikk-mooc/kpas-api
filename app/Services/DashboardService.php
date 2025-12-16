<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class DashboardService {
    public function getDashboardHtml($courseId, $settings) {
        logger("DashboardService::getDashboardHtml");

        return view('main.dashboard')
            ->withCourseId($courseId)
            ->withSettings($settings);
    }
}
