<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\GroupEnrollmentService;
use Illuminate\Http\Request;

class GroupEnrollmentController extends Controller
{

    public function getGroupEnrollmentCount(int $courseId, Request $request): SuccessResponse
    {
        $enrollmentService = new GroupEnrollmentService();
        logger($request);
        $data = $enrollmentService->getGroupEnrollmentData($courseId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }
}
