<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Enrollment\EnrollUserRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasDbRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EnrollmentController extends Controller
{
    /**
     * @var CanvasDbRepository
     */
    protected $canvasDbRepository;

    public function __construct(CanvasDbRepository $canvasDbRepository)
    {
        $this->canvasDbRepository = $canvasDbRepository;
    }

    public function index(): SuccessResponse
    {
        $settings = session()->get('settings');
        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');
        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        logger("EnrollmentController.index user_id=" . $userId . " course_id=" . $courseId);
        $data = $this->canvasDbRepository->getUserEnrollmentsByCourse($userId, $courseId);
        return new SuccessResponse($data);
    }

    public function store(EnrollUserRequest $request): SuccessResponse
    {
        $settings = session()->get('settings');
        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');
        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');

        logger("EnrollmentController.store user_id=" . $userId . " course_id=" . $courseId);
        $this->canvasDbRepository->enrollUserToCourse($userId, $courseId, $request->get('role'));
        return new SuccessResponse([]);
    }
}
