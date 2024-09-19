<?php

namespace App\Http\Controllers;

use App\Exceptions\CanvasException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Services\CanvasGraphQLService;
use Illuminate\Contracts\Support\Responsable;

class CourseController extends Controller
{

    /**
     * @var CanvasService
     */
    protected $canvasService;

    /**
     * @var CanvasGraphQLService
     */
    protected $canvasGraphQLService;

    public function __construct(CanvasService $canvasService, CanvasGraphQLService $canvasGraphQLService)
    {
        $this->canvasService = $canvasService;
        $this->canvasGraphQLService = $canvasGraphQLService;
    }

    public function index(int $courseId): Responsable
    {
        try {
            $courseInfo = $this->canvasService->getCourse($courseId);
        } catch (CanvasException $e) {
            return new ErrorResponse("Could not find course with supplied ID.");
        }

        $courseOwnerId = $courseInfo->account_id;
        $udirCanvasParentAccountId = config('canvas.account_id');

        try {
            $courseOwnerIsChildAccountOfUdir = $this->canvasService->accountIsChildOf($udirCanvasParentAccountId, $courseOwnerId);
        } catch (CanvasException $e) {
            return new ErrorResponse("Could not determine parent account of owner to course with supplied ID.");
        }

        return new SuccessResponse($courseOwnerIsChildAccountOfUdir);
    }

    public function getStudentCount(string $courseId)
    {
        try {
            return new SuccessResponse($this->canvasService->getTotalStudents($courseId)['antallBrukere']);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    public function getModulesList(string $courseId)
    {
        try {
            $response = $this->canvasGraphQLService->modulesConnection($courseId);
            if (!$response) return [];

            $course = $response['data']['course'];
            $modules = $course['modulesConnection']['nodes'];

            return new SuccessResponse(empty($modules) ? [] : $modules);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
