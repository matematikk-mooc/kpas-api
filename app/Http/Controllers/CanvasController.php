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

    public function getCourseModuleItems($courseId, $moduleId)
    {
        $moduleItems = $this->canvasService->getCourseModuleItems($courseId, $moduleId);
        return new SuccessResponse($moduleItems);
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
    public function getCourseFrontPage($courseId)
    {
        $frontPage = $this->canvasService->getCourseFrontPage($courseId);
        return new SuccessResponse($frontPage);
    }
    public function getCoursePageContent($courseId, $pageId)
    {
        $page = $this->canvasService->getCoursePageContent($courseId, $pageId);
        return new SuccessResponse($page);
    }
    public function getCourseDiscussionTopics($courseId)
    {
        $topics = $this->canvasService->getCourseDiscussionTopics($courseId);
        return new SuccessResponse($topics);
    }
    public function getAssignmentsForCourse($courseId)
    {
        $assignments = $this->canvasService->getAssignmentsForCourse($courseId);
        return new SuccessResponse($assignments);
    }
    public function getLinksValidationForCourse($courseId)
    {
        $links = $this->canvasService->getLinksValidationForCourse($courseId);
        return new SuccessResponse($links);
    }
}
