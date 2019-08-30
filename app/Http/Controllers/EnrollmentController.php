<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Enrollment\EnrollUserRequest;
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

    public function enrollUser(EnrollUserRequest $request): void
    {
        $county = new GroupDto($request->input('county'));
        $community = new GroupDto($request->input('community'));
        $school = new GroupDto($request->input('school'));

        $groups = new Collection();

        $groups->push($this->canvasDbRepository->getOrCreateGroup($county));
        $groups->push($this->canvasDbRepository->getOrCreateGroup($community));
        $groups->push($this->canvasDbRepository->getOrCreateGroup($school));

        $userId = Arr::get(session()->get('settings'), 'canvas_user_id');

        $groups->each(function (GroupDto $group) use ($userId) {
            $this->canvasDbRepository->addUserToGroup($userId, $group);
        });

        $courseId = Arr::get(session()->get('settings'), 'canvas_course_id');

        $this->canvasDbRepository->addUserToCourse($userId, $courseId);
    }
}
