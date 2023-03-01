<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function moduleStatistics(Request $request, int $courseId): SuccessResponse
    {
        $moduleService = new ModuleService();
        if($request->has('group')){
            $data = $moduleService->getModuleStatisticsByGroup($courseId, $request->group);
        }
        else{
            $data = $moduleService->getModuleStatistics($courseId);
        }
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function moduleStatisticsCount(Request $request, int $courseId): SuccessResponse
    {
        $moduleService = new ModuleService();
        $data = $moduleService->getModuleStatisticsCount($courseId);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }
}
