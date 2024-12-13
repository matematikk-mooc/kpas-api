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
            $accountIds = [99, 100, 102, 103, 137, 138, 139, 145];
            $courseSettingsRepository = new CourseSettingsRepository();
            $returnData = [
                "courses" => [],
                "settings" => [],
                "filters" => [],
                "highligthed" => []
            ];

            $courses = $this->canvasService->getAllPublishedCourses();
            $coursesCanvasSettings = $this->canvasService->getAllAccountCourses(138);
            $courseSettings = $courseSettingsRepository->getCourseSettingsForAllCourses();    
            $courseFilters = $courseSettingsRepository->getFilters();
            $highligthedCourse = $courseSettingsRepository->getHighLightedCourse();
            
            $coursesFiltered = [];
            foreach ($courses as $courseKey => $courseItem) {
                $courseObject = $courseItem->course;
                $courseAccountId = $courseObject->account_id;
                if (in_array($courseAccountId, $accountIds)) {
                    unset($courseItem->course->tab_configuration);
                    unset($courseItem->course->settings);
                    unset($courseItem->course->stuck_sis_fields);

                    $courseCanvasSettings = null;
                    foreach ($coursesCanvasSettings as $key => $value) {
                        if ($value->id == $courseObject->id) {
                            $courseCanvasSettings = $value;
                            break;
                        }
                    }

                    $courseItem->course->image_download_url = $courseCanvasSettings != null ? $courseCanvasSettings->image_download_url : null;
                    array_push($coursesFiltered, $courseItem);
                }
            }

            $returnData["courses"] = $coursesFiltered;
            $returnData["settings"] = $courseSettings;
            $returnData["filters"] = $courseFilters;
            $returnData["highligthed"] = $highligthedCourse;

            return new SuccessResponse($returnData);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
