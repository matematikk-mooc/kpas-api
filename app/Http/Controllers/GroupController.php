<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Group\AddUserRequest;
use App\Http\Requests\Group\AddUserToGroupsRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasRepository;
use App\Repositories\CanvasDbRepository;
use App\Services\DataportenService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class GroupController extends Controller
{
    /**
     * @var DataportenService
     */
    protected $dataportenService;
    /**
     * @var CanvasRepository
     */
    protected $canvasRepository;

    public function __construct(DataportenService $dataportenService, CanvasDbRepository $canvasRepository)
    {
        $this->dataportenService = $dataportenService;
        $this->canvasRepository = $canvasRepository;
    }

    public function getUserGroups(): SuccessResponse
    {
        $userId = Arr::get(session('settings'), 'custom_canvas_user_id');
        $groups = $this->canvasRepository->getUserGroups($userId);

        return new SuccessResponse($groups);
    }

    public function addUserToGroups(AddUserToGroupsRequest $request): SuccessResponse
    {
        $county = new GroupDto($request->input('county'));
        $community = new GroupDto($request->input('community'));
        $school = new GroupDto($request->input('school'));

        $groups = new Collection();

        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');

        $groupCategories = $this->canvasRepository->getGroupCategories($courseId);
        $county->setCategoryId($this->findGroupCategory(
            $groupCategories,
            config('canvas.county_name')
        )->id);
        $community->setCategoryId($this->findGroupCategory(
            $groupCategories,
            config('canvas.community_name')
        )->id);
        $school->setCategoryId($this->findGroupCategory(
            $groupCategories,
            config('canvas.school_name')
        )->id);

        $groups->push($this->canvasRepository->getOrCreateGroup($county));
        $groups->push($this->canvasRepository->getOrCreateGroup($community));
        $groups->push($this->canvasRepository->getOrCreateGroup($school));

        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        $groups->each(function (GroupDto $group) use ($userId) {
            $this->canvasRepository->addUserToGroup($userId, $group);
        });

        return new SuccessResponse($groups->toArray());
    }

    public function addUser(AddUserRequest $request): SuccessResponse
    {
        $group = new GroupDto($request->input('group'));
        $unenrollForm = $request->input('unenrollFrom', []);

        $this->dataportenService->setAccessKey($request->header('X-Dataporten-Token'));

        $dataportenUserInfo = $this->dataportenService->getUserInfo();

        $feideId = $this->dataportenService->getFeideId($dataportenUserInfo);

        $canvasUser = $this->canvasRepository->getUserByFeideId($feideId);

        $this->canvasRepository->addUserToGroupInSection($canvasUser->id, $group, Arr::get($unenrollForm, 'unenrollmentIds', []));

        return new SuccessResponse('Success');
    }

    public function categories($groupId): SuccessResponse
    {
        $result = $this->canvasRepository->getGroupCategories($groupId);

        return new SuccessResponse($result);
    }

    protected function findGroupCategory($groupCategories, $name)
    {
        return collect($groupCategories)->first(function ($groupCategory) use ($name) {
            return $groupCategory->name === $name;
        });
    }
}
