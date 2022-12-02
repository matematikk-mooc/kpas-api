<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function quizzesStatistics(int $courseId): SuccessResponse
    {   
        logger("QuizController.quizzesStatistics");
        logger($courseId);
        $quizService = new QuizService();
        $data = $quizService->getCourseQuizzesStatistics($courseId);
        $res = $data->getBody()->getContents();        
        logger("Returning quizzes data");
        logger($res);
        return new SuccessResponse($res);
    }

    public function quizStatistics(int $courseId, int $quizId): SuccessResponse
    {   
        logger("QuizController.quizStatistics");
        $quizService = new QuizService();
        $data = $quizService->getCourseQuizIdStatistics($courseId, $quizId);
        return new SuccessResponse($data);
    }
}
