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
    public function getGroupStatistics(int $courseId)
    {
        $categories = $this->canvasDbRepository->getGroupCategories($courseId);
        $groupStatistics = array();
        foreach ($categories as $category) {
            $groupStatistics[$category->name] = $this->canvasDbRepository->getNoOfGroups($category->id);
        }
        return $groupStatistics;
    }
    public function getStatistics($courseId) 
    {
        $data = $this->canvasDbRepository->getTotalStudents($courseId);
        $groupStatistics = $this->getGroupStatistics($courseId);
        $data["groups"] = $groupStatistics;
        return $data;
    }
    public function index(int $courseId): SuccessResponse
    {
        logger("StatisticsController.index");
        $data = $this->getStatistics($courseId);
        return new SuccessResponse($data);
    }
    public function webindex(int $courseId)
    {
        $data = $this->getStatistics($courseId);
        return view('statistics.statistics', $data);
    }
}
