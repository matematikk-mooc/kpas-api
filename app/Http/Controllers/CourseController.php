<?php

namespace App\Http\Controllers;

use App\Exceptions\CanvasException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use Illuminate\Contracts\Support\Responsable;

class CourseController extends Controller
{

    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
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

    public function getStudentCount(string $courseId) {
        try {
            return new SuccessResponse($this->canvasService->getTotalStudents($courseId)['antallBrukere']);
        } catch (Exeption $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
