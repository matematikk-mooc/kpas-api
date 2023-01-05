<?php

namespace App\Http\Controllers;


use App\Http\Requests\Survey\AddSurveyRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\SurveyRepository;


class SurveyController extends Controller
{

    public function create(AddSurveyRequest $request): SuccessResponse {
        logger("SurveyController@create");
        logger($request);
        $surveyRepository = new SurveyRepository();
        $id = $surveyRepository->createSurvey($request);

        return new SuccessResponse($id);
    }

}
