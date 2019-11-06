<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;

class StatisticsController extends Controller
{
    protected $canvasDbRepository;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }
    public function index(string $courseId): SuccessResponse
    {
        logger("StatisticsController.index");
        $statistics = collect($this->canvasService->getStatistics($courseId));
        return new SuccessResponse($statistics);
    }
}
