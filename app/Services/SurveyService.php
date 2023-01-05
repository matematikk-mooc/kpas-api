<?php
namespace App\Services;


class SurveyService
{

    public function getSurveyBlade($settings) {
        logger("getQuizData");

        return view('main.survey')->withSettings($settings);
    }

}
