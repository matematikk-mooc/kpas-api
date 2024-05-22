<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Repositories\CourseSettingsRepository;

class BffController extends Controller
{
    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }

    // V2: SWR caching of response to speed up endpoint
    // V2: Run all calls to get courses, settings, filters and highligthed course in parallel
    // V2: Remove unused fields to reduce payload size for client side to download
    public function getCoursesForFrontpage(bool $skip_cache = false)
    {
        try {
            $courseSettingsRepository = new CourseSettingsRepository();
            $returnData = [
                "courses" => [],
                "settings" => [],
                "filters" => [],
                "highligthed" => []
            ];

            $courses = $this->canvasService->getAllPublishedCourses();
            $courseSettings = $courseSettingsRepository->getCourseSettingsForAllCourses();    
            $courseFilters = $courseSettingsRepository->getFilters();
            $highligthedCourse = $courseSettingsRepository->getHighLightedCourse();
            
            $returnData["courses"] = $courses;
            $returnData["settings"] = $courseSettings;
            $returnData["filters"] = $courseFilters;
            $returnData["highligthed"] = $highligthedCourse;

            $this->cachedCourses = $returnData;
            return new SuccessResponse($returnData);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
