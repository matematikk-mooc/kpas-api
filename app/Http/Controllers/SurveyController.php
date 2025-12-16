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
        $surveyRepository = new SurveyRepository();
        $id = $surveyRepository->createSurvey($request);
        logger("SurveyController::create survey_id=$id");

        return new SuccessResponse($id);
    }

    public function getCourseSurveys(Request $request, $courseId): SuccessResponse
    {
        logger("SurveyController::getCourseSurveys course_id=$courseId");
        $surveyRepository = new SurveyRepository();
        if ($request->has('group')) {
            $result = $surveyRepository->getSurveysFilteredOnGroup(intval($courseId), $request->group);
            return new SuccessResponse($result);
        }
        $result = $surveyRepository->getSurveys(intval($courseId));
        return new SuccessResponse($result);
    }

    //Method to exclude essay questions in response
    public function getCourseSurveysWithoutOpenAnswerResponses(Request $request, $courseId)
    {
        $surveyRepository = new SurveyRepository();
        try {
            if ($request->has('group')) {
                $result = $surveyRepository->getSurveysFilteredOnGroupExcludingEssays(intval($courseId), $request->group);
                return new SuccessResponse($result);
            }
        }
        catch(\Exception $e) {
            logger("SurveyController::getCourseSurveysWithoutOpenAnswerResponses error=" . $e->getMessage());
        }


    }

    public function getSurveyWithQuestions(Request $request): SuccessResponse
    {
        $courseIdParam = $request->course_id;
        $moduleIdIParam = $request->module_id;
        $courseIdInt = !empty($courseIdParam) ? intval($courseIdParam) : null;
        $moduleIdInt = !empty($moduleIdIParam) ? intval($moduleIdIParam) : null;

        if ($courseIdInt == null) {
            return response()->json(['message' => 'Missing course_id'], 400);
        }

        logger("SurveyController::getSurveyWithQuestions course_id=$courseIdInt module_id=$moduleIdInt");
        $surveyRepository = new SurveyRepository();
        $result = $surveyRepository->getSurveysWithQuestions($courseIdInt, $moduleIdInt);
        return new SuccessResponse($result);
    }

    public function getUserSubmission(int $surveyId, int $userId): SuccessResponse
    {
        logger("SurveyController@getUserSubmission");
        $surveyRepository = new SurveyRepository();
        $result = $surveyRepository->getStudentSubmission($surveyId, $userId);
        return new SuccessResponse($result);
    }

    public function createUserSubmission(AddUserSubmissionRequest $request, int $surveyId)
    {
        logger("SurveyController::createUserSubmission survey_id=$surveyId");

        $settings = session()->get('settings');
        $userId = intval($settings['custom_canvas_user_id']);

        $surveyRepository = new SurveyRepository();
        try {
            $surveyRepository->createUserSubmission($surveyId, $userId, $request->answers);
        } catch (SurveyAlreadySubmittedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            logger("SurveyController::createUserSubmission error=" . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return new SuccessResponse(null);
    }

    public function deleteUserSubmission(Request $request, int $surveyId)
    {
        logger("SurveyController::deleteUserSubmission survey_id=$surveyId");

        $settings = session()->get('settings');
        $userId = intval($settings['custom_canvas_user_id']);

        $surveyRepository = new SurveyRepository();
        $surveyRepository->deleteUserSubmission($surveyId, $userId);

        return new SuccessResponse(null);
    }


}
