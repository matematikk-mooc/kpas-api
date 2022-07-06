<?php

namespace App\Services;

class StatisticsService
{
    public function getStatisticsHtml($settings) {
        logger("getStatisticsHtml");
        logger($settings);

        /////
        $statistics = []; //Get statistics here.
        /////

        return view('main.statistics')->withStatistics($statistics)->withSettings($settings);
    }
}
