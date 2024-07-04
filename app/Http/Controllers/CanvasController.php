<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;

class CanvasController extends Controller
{
     /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;

    }

    public function getModuleTitles($courseId)
    {
        $modules = $this->canvasService->getCourseModules($courseId);
        return new SuccessResponse($this->stripModulesDataToTitles($modules));
    }

    private function stripModulesDataToTitles($modules)
    {
        $titles = [];
        foreach ($modules as $module) {
            if($module->published == true)
                array_push($titles, (object) ['name' => $module->name,
                                'id' => $module->id,
                                'position' => $module->position,
                                'published' => $module->published]);

        }
        return $titles;
    }

    public function getCourseData($courseId)
    {
        $course = $this->canvasService->getCourseData($courseId);
        return new SuccessResponse($course);
    }
    public function getCoursePages($courseId)
    {
        $modules = $this->canvasService->getCoursePages($courseId);
        return new SuccessResponse($modules);
    }
    public function getCoursePageContent($courseId, $pageId)
    {
        $page = $this->canvasService->getCoursePageContent($courseId, $pageId);
        return new SuccessResponse($page);
    }
}
