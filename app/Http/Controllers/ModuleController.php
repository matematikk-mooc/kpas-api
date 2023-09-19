<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    /**
     * @var ModuleService
     */
    private $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function moduleStatistics(Request $request, int $courseId): SuccessResponse
    {
        if($request->has('group')){
            $data = $this->moduleService->getModuleStatisticsByGroup($courseId, $request->group);
        }
        else{
            $data = $this->moduleService->getModuleStatistics($courseId);
        }
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function moduleStatisticsCount(Request $request, int $courseId): SuccessResponse
    {
        $data = $this->moduleService->getModuleStatisticsCount($courseId);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function moduleStatisticsPerDate(Request $request, int $moduleId){
        $data = $this->moduleService->getModuleStatisticsPerDate($moduleId);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }
}
