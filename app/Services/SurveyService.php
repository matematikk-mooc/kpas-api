<?php
namespace App\Services;


class SurveyService
{

    public function getSurveyBlade($settings)
    {
        return view('main.survey')->withSettings($settings);
    }

}
