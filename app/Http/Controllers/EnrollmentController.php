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
        $county = new GroupDto($request->input('county'));
        $community = new GroupDto($request->input('community'));
        $school = new GroupDto($request->input('school'));

        $groups = new Collection();

        $courseId = Arr::get(session()->get('settings'), 'canvas_course_id');

        $groupCategories = $this->canvasDbRepository->getGroupCategories($courseId);
        $county->setCategoryId($this->findGroupCategory(
            $groupCategories,
            env('CANVAS_COUNTY_GROUP_CATEGORY_NAME')
        )->id);
        $community->setCategoryId($this->findGroupCategory(
            $groupCategories,
            env('CANVAS_COMMUNITY_GROUP_CATEGORY_NAME')
        )->id);
        $school->setCategoryId($this->findGroupCategory(
            $groupCategories,
            env('CANVAS_SCHOOL_GROUP_CATEGORY_NAME')
        )->id);

        $groups->push($this->canvasDbRepository->getOrCreateGroup($county));
        $groups->push($this->canvasDbRepository->getOrCreateGroup($community));
        $groups->push($this->canvasDbRepository->getOrCreateGroup($school));

        $userId = Arr::get(session()->get('settings'), 'canvas_user_id');

        $groups->each(function (GroupDto $group) use ($userId) {
            $this->canvasDbRepository->addUserToGroup($userId, $group);
        });


        $this->canvasDbRepository->enrollUserToCourse($userId, $courseId);

        return new SuccessResponse([]);
    }

    public function getUserEnrollments(): SuccessResponse
    {
        $userId = Arr::get(session()->get('settings'), 'canvas_user_id');

        $data = $this->canvasDbRepository->getUserEnrollments($userId);

        return new SuccessResponse($data);
    }

    protected function findGroupCategory($groupCategories, $name)
    {
        return collect($groupCategories)->first(function ($groupCategory) use ($name) {
            return $groupCategory->name === $name;
        });
    }
}
