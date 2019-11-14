<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasDbRepository;

class StatisticsController extends Controller
{
    protected $canvasDbRepository;

    public function __construct(CanvasDbRepository $canvasDbRepository)
    {
        $this->canvasDbRepository = $canvasDbRepository;
    }
    public function index(int $courseId): SuccessResponse
    {
        logger("StatisticsController.index");
        $categories = $this->canvasDbRepository->getGroupCategories($courseId);
        $groupStatistics = array();
        foreach ($categories as $category) {
            $groupStatistics[$category->name] = $this->canvasDbRepository->getNoOfGroups($category->id);
        }
        $totalStudents = $this->canvasDbRepository->getTotalStudents($courseId);
        $statistics = collect([$totalStudents, $groupStatistics]);
        return new SuccessResponse($statistics);
    }
}
