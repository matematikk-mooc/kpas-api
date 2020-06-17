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
        logger("GroupController.index");
//        $userId = Arr::get(session('settings'), 'custom_canvas_user_id');
        $courseId = Arr::get(session('settings'), 'custom_canvas_course_id');
        //It is not possible to fetch groups for a user with the token we have.
        //        $groups = collect($this->canvasRepository->getUserGroups($userId));
        logger("custom_canvas_course_id:" . $courseId);
        $categories = collect($this->canvasRepository->getGroupCategories($courseId));
        logger("Returning categories: " . $categories);
        return new SuccessResponse($categories);

        //Old way below. Note that this endpoint now returns the categories and that the client has to
        //fetch the users groups and then merge it with the categories. So this method should be renamed from
        //group to groupcategories or something like that.
        /*
        $categorizedGroups = $categories->mapWithKeys(function ($category) use ($groups) {
            return [$category-> name => $groups->first(function($group) use ($category) {
                return $group->group_category_id === $category->id;
            })];
        });
        logger('categorizedGroups:' . $categorizedGroups);

        return new SuccessResponse($categorizedGroups);
        */
    }

    public function bulkStore(AddUserToGroupsRequest $request): SuccessResponse
    {
        $groups = new Collection();

        $county = new GroupDto($request->input('county'));
        $community = new GroupDto($request->input('community'));
        $institution = new GroupDto($request->input('school'));

        $courseId = Arr::get(session()->get('settings'), 'custom_canvas_course_id');

        $role = $request->get('role');

        if ($role === config('canvas.principal_role')) {
            $groups = $this->createPrincipalGroups($groups, $county, $community, $role);
        }

        if ($request->has('faculty')) {
            $faculty = $request->get('faculty');
            if($faculty != "") {
                logger("Request has faculty:" . $request);
                $faculties = $this->createFacultyGroups($county, $community, $faculty);
                $groups = $groups->merge($faculties);
            }
        }

        logger(session('settings'));

        $county->setCategoryId($this->getFromSession('custom_county_category_id'));
        $community->setCategoryId($this->getFromSession('custom_community_category_id'));
        $institution->setCategoryId($this->getFromSession('custom_institution_category_id'));

        $groups = $groups->merge([$county, $community, $institution]);

        $groups = $groups->map(function (GroupDto $group) {
            return $this->canvasRepository->getOrCreateGroup($group);
        });

        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        $currentGroups = $request->input('currentGroups');
        //logger("CurrentGroups" . print_r($currentGroups, true));
        $this->canvasRepository->removeUserFromGroups($userId, $currentGroups);

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

    protected function createPrincipalGroups(
        Collection $groups,
        GroupDto $county,
        GroupDto $community,
        string $role
    )
    {
        $countyLeaders = $this->createPrefixedGroup($county, $role);
        $communityLeaders = $this->createPrefixedGroup($community, $role);

        $countyLeaders->setCategoryId($this->getFromSession('custom_county_principals_category_id'));
        $communityLeaders->setCategoryId($this->getFromSession('custom_community_principals_category_id'));

        return $groups->merge([$communityLeaders, $countyLeaders]);
    }

    protected function createFacultyGroups(GroupDto $county, GroupDto $community, $faculty)
    {
        $countyFaculty = $this->createPrefixedGroup($county, $faculty);
        $communityFaculty = $this->createPrefixedGroup($community, $faculty);

        $countyFaculty->setCategoryId($this->getFromSession('custom_county_faculty_category_id'));
        $communityFaculty->setCategoryId($this->getFromSession('custom_community_faculty_category_id'));

        return [$countyFaculty, $communityFaculty];
    }

    protected function createPrefixedGroup(GroupDto $dto, string $prefix): GroupDto
    {
        $dto = new GroupDto($dto->toArray());
        $dto->setName($prefix . ' ' . $dto->getName());
        return $dto;
    }

    protected function getFromSession(string $key)
    {
        return Arr::get(session('settings'), $key);
    }
}
