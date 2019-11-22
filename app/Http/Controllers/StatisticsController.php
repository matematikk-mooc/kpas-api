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
    public function courseCount(int $courseId) : SuccessResponse 
    {
        $data = $this->canvasDbRepository->getTotalStudents($courseId);
        return new SuccessResponse($data);
    }
    public function groupCategory(int $categoryId): SuccessResponse
    {
        logger("StatisticsController.groupCategory");
        $groups = $this->canvasDbRepository->getGroupsInGroupCategory($categoryId);
//        logger(print_R($groups, true));
        $data["groups"] = $groups;
        return new SuccessResponse($data);
    }
    public function groupCategoryCount(int $categoryId): SuccessResponse
    {
        logger("StatisticsController.groupCategoryCount");
        $data = $this->canvasDbRepository->getNoOfGroups($categoryId);
        return new SuccessResponse($data);
    }
    public function webindex(int $courseId)
    {
        $data = $this->getStatistics($courseId);
        return view('statistics.statistics', $data);
    }
}
