<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Models\Group;
use App\Http\Requests\Group\AddUserRequest;
use App\Http\Requests\Group\AddUserToGroupsRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasRepository;
use App\Repositories\CanvasDbRepository;
use App\Services\DataportenService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Responses\ErrorResponse;


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
        logger("bulkStore: " . print_r(session('settings'),true));
        $groups = new Collection();

        $county = new GroupDto($request->input('county'));
        logger("County group:" . print_r($request->input('county'), true));
        $county->setCategoryId($this->getFromSession('custom_county_category_id'));

        $community = new GroupDto($request->input('community'));
        $community->setCategoryId($this->getFromSession('custom_community_category_id'));

        $groups = $groups->merge([$county, $community]);

        $institutionPresent =  $request->has('institution');
        logger("INSTITUTION present: " . $institutionPresent ? "true" : "false");

        if($institutionPresent) {
            $institution = new GroupDto($request->input('institution'));
            $institution->setCategoryId($this->getFromSession('custom_institution_category_id'));
            $groups = $groups->merge([$institution]);
        }

        $role = $request->get('role');
        if ($role === config('canvas.principal_role')) {
            //KURSP-378 temporary fix - customInstitutionLeaderDescription should be used instead in future.
            if($institutionPresent) {
                logger("Group description: " . $institution->getDescription());
                if((strpos($institution->getDescription(), 'kindergarten') !== false)) {
                    $role = "Leder";
                }
            }
            $customInstitutionLeaderDescription = Arr::get(session()->get('settings'), 'custom_institution_leader_description');
            if($customInstitutionLeaderDescription) {
                $role = $customInstitutionLeaderDescription;
            }

            $groups = $this->createPrincipalGroups($groups, $county, $community, $role);
        }

        if ($request->has('faculty')) {
            $faculty = $request->get('faculty');
            if($faculty != "") {
                logger("Request has faculty:" . $request);
                $courseId = $request->get('courseId');
                $faculties = $this->createFacultyGroups($courseId, $county, $community, $faculty);
                $groups = $groups->merge($faculties);
            }
        }

        logger(print_r($groups, true));
        $groups = $groups->map(function (GroupDto $group) {
            logger("getOrCreateGroup" . $group->getName());
            return $this->canvasRepository->getOrCreateGroup($group);
        });

        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');

        $currentGroups = $request->input('currentGroups');
        //logger("CurrentGroups" . print_r($currentGroups, true));
        $this->canvasRepository->removeUserFromGroups($userId, $currentGroups);

        $groups->each(function (GroupDto $group) use ($userId) {
            try {
                $this->canvasRepository->addUserToGroup($userId, $group);
            } catch (\Throwable $th) {
                if (!str_contains($th->getMessage(), 'not found')) 
                    throw $th;

                logger("Group of ID " . $group->getId() . " not found while bulk adding user to groups.");
            }
        });

        //Note that the response does not contain the groups because they are objects.
        //Could be fixed, but the frontend doesn't use the answer as of today.
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

    protected function createFacultyGroups($courseId, GroupDto $county, GroupDto $community, $faculty)
    {
        $countyFaculty = $this->createPrefixedFacultyGroup($county, $faculty);
        $communityFaculty = $this->createPrefixedFacultyGroup($community, $faculty);

        $countyFaculty->setCategoryId($this->getFromSession('custom_county_faculty_category_id'));
        $communityFaculty->setCategoryId($this->getFromSession('custom_community_faculty_category_id'));

        $nationalCategoryId = $this->getFromSession('custom_national_faculty_category_id');
        if($nationalCategoryId) {
            logger("Create national faculty group.");
            $facultyStripped = $this->getStrippedFacultyName($faculty);
            $nationalFacultyDescription = "faculty:" . $facultyStripped . ":courseId:" . $courseId . ":national" ;
            $nationalFacultyArray = array("Name"=>$faculty, "Description"=>$nationalFacultyDescription);
            $nationalFaculty = new GroupDto($nationalFacultyArray);
            $nationalFaculty->setCategoryId($nationalCategoryId);
            $nationalFaculty->setCourseId($courseId);
            return [$nationalFaculty, $countyFaculty, $communityFaculty];
        }

        return [$countyFaculty, $communityFaculty];
    }

    protected function getStrippedFacultyName($faculty) {
        return strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $faculty));
    }

    protected function createPrefixedFacultyGroup(GroupDto $dto, string $faculty): GroupDto
    {
        $dto = $this->createPrefixedGroup($dto, $faculty);
        $dto = new GroupDto($dto->toArray());
        $facultyStripped = $this->getStrippedFacultyName($faculty);
        $dto->setDescription('faculty:' . $facultyStripped . ':' . $dto->getDescription());
        return $dto;
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

    public function getStoredGroups()
    {
        return Group::all();
    }

    public function getCourseGroups($courseId)
    {
        return Group::where('course_id', $courseId)->get();
    }

    public function getCourseGroupsByCategory(Request $request, $courseId, $categoryId)
    {
        if($request->get('county_id')){
            return Group::where([
                ['course_id', $courseId],
                ['category_id', $categoryId],
                ['county_id', $request->get('county_id')]
            ])->get();
        }
        elseif($request->get('community_id')) {
            return Group::where([
                ['course_id', $courseId],
                ['category_id', $categoryId],
                ['community_id', $request->get('community_id')]
            ])->get();
        }
        return Group::where([
            ['course_id', $courseId],
            ['category_id', $categoryId]
        ])->get();
    }

    public function getStudentCount(string $groupId) {
        try {
            return new SuccessResponse($this->canvasRepository->getTotalStudentsByGroup($groupId)['antallBrukere']);
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

}
