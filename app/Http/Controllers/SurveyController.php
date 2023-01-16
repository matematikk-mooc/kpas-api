<?php

namespace App\Http\Controllers;


use App\Exceptions\Survey\SurveyAlreadySubmittedException;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Http\Requests\Survey\AddUserSubmissionRequest;
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

    public function createUserSubmission(AddUserSubmissionRequest $request, int $surveyId) {
        logger("SurveyController@createUserSubmission");

        $settings = session()->get('settings');
        $userId = intval($settings['custom_canvas_user_id']);

        $surveyRepository = new SurveyRepository();
        try {
            $surveyRepository->createUserSubmission($request->answers, $surveyId, $userId);
        } catch (SurveyAlreadySubmittedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            logger("Error creating user submission - Returning 500 Internal Server Error: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return new SuccessResponse(null);
    }

    public function deleteUserSubmission(Request $request, int $surveyId) {
        logger("SurveyController@deleteUserSubmission");

        $settings = session()->get('settings');
        $userId = intval($settings['custom_canvas_user_id']);

        $surveyRepository = new SurveyRepository();
        $surveyRepository->deleteUserSubmission($surveyId, $userId);

        return new SuccessResponse(null);
    }


}
