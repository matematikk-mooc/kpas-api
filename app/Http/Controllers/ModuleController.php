<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function moduleStatistics(int $courseId): SuccessResponse
    {
        $moduleService = new ModuleService();
        $data = $moduleService->getModuleStatistics($courseId);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($data);

    }

}