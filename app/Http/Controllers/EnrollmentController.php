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

    public function enrollUser(EnrollUserRequest $request): SuccessResponse
    {
        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');
        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');

        $this->canvasDbRepository->enrollUserToCourse($userId, $courseId, $request->get('role'));

        return new SuccessResponse([]);
    }

    public function getUserEnrollments(): SuccessResponse
    {
        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        $data = $this->canvasDbRepository->getUserEnrollments($userId);

        return new SuccessResponse($data);
    }
}
