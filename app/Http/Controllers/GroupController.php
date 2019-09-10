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
     * @var CanvasRepository|CanvasDbRepository
     */
    protected $canvasRepository;

    public function __construct(DataportenService $dataportenService, CanvasDbRepository $canvasRepository)
    {
        $this->dataportenService = $dataportenService;
        $this->canvasRepository = $canvasRepository;
    }

    public function index(): SuccessResponse
    {
        $userId = Arr::get(session('settings'), 'custom_canvas_user_id');
        $courseId = Arr::get(session('settings'), 'custom_canvas_course_id');
        $groups = collect($this->canvasRepository->getUserGroups($userId));
        $categories = collect($this->canvasRepository->getGroupCategories($courseId));

        $categorizedGroups = $categories->mapWithKeys(function ($category) use ($groups) {
            return [$category-> name => $groups->first(function($group) use ($category) {
                return $group->group_category_id === $category->id;
            })];
        });

        return new SuccessResponse($categorizedGroups);
    }

    public function bulkStore(AddUserToGroupsRequest $request): SuccessResponse
    {
        $groups = new Collection();

        $county = new GroupDto($request->input('county'));
        $community = new GroupDto($request->input('community'));
        $school = new GroupDto($request->input('school'));

        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');

        $role = $request->get('role');

        $groupCategories = $this->canvasRepository->getGroupCategories($courseId);

        if ($role === config('canvas.principal_role')) {
            $groups = $this->createPrincipalGroups($groups, $county, $community, $groupCategories, $role);
        }

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

        $groups = $groups->merge([$county, $community, $school]);

        $groups = $groups->map(function (GroupDto $group) {
            return $this->canvasRepository->getOrCreateGroup($group);
        });

        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        $this->canvasRepository->removeUserGroups($userId, $courseId);

        $groups->each(function (GroupDto $group) use ($userId) {
            $this->canvasRepository->addUserToGroup($userId, $group);
        });

        return new SuccessResponse($groups->toArray());
    }

    public function store(AddUserRequest $request): SuccessResponse
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

    protected function findGroupCategory($groupCategories, $name)
    {
        return collect($groupCategories)->first(function ($groupCategory) use ($name) {
            return $groupCategory->name === $name;
        });
    }

    protected function createPrincipalGroups(
        Collection $groups,
        GroupDto $county,
        GroupDto $community,
        array $groupCategories,
        string $role
    ) {
        $countyLeadersData = $county->toArray();
        $communityLeadersData = $community->toArray();

        $countyLeadersData['name'] = $role . ' ' . $countyLeadersData['name'];
        $communityLeadersData['name'] = $role . ' ' . $communityLeadersData['name'];
        $countyLeaders = new GroupDto($countyLeadersData);
        $communityLeaders = new GroupDto($communityLeadersData);

        $countyLeaders->setCategoryId($this->findGroupCategory(
            $groupCategories,
            config('canvas.county_leaders_name')
        )->id);

        $communityLeaders->setCategoryId($this->findGroupCategory(
            $groupCategories,
            config('canvas.community_leaders_name')
        )->id);

        return $groups->merge([$communityLeaders, $countyLeaders]);
    }
}
