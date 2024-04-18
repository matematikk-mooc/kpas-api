<?php
namespace App\Services;


class SurveyService
{

    public function getSurveyBlade($settings)
    {
        logger("getSurveyData");

        return view('main.survey')->withSettings($settings);
    }

}
