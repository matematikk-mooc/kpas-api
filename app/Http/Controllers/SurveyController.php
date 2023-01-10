<?php

namespace App\Http\Controllers;


use App\Http\Requests\Survey\AddSurveyRequest;
use App\Repositories\SurveyRepository;
use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;


class SurveyController extends Controller
{

    public function create(AddSurveyRequest $request): SuccessResponse 
    {
        logger("SurveyController@create");
        logger($request);
        $surveyRepository = new SurveyRepository();
        $id = $surveyRepository->createSurvey($request);

        return new SuccessResponse($id);
    }

    public function getCourseSurveys(Request $request, int $courseId): SuccessResponse
    {
        logger("SurveyController@getCourseSurveys");
        $surveyRepository = new SurveyRepository();
        if ($request->has('group')) {
            $result = $surveyRepository->getSurveysFilteredOnGroup($courseId, $request->group);
            return new SuccessResponse($result);
        }
        $result = $surveyRepository->getSurveys($courseId);
        return new SuccessResponse($result);
    }

    public function getUserSubmission(int $surveyId, int $userId): SuccessResponse
    {
        logger("SurveyController@getUserSubmission");
        $surveyRepository = new SurveyRepository();
        $result = $surveyRepository->getStudentSubmission($surveyId, $userId);
        return new SuccessResponse($result);
    }

}
