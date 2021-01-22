<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Requests\Group\AddUserRequest;
use App\Repositories\CanvasRepository;
use App\Services\DataportenService;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * @var DataportenService
     */
    protected $dataportenService;

    /**
     * @var CanvasRepository
     */
    protected $canvasRepository;

    public function __construct(DataportenService $dataportenService, CanvasRepository $canvasRepository)
    {
        $this->dataportenService = $dataportenService;
        $this->canvasRepository = $canvasRepository;
    }

    public function index(Request $request)
    {
        if (!$request->session()->exists('userInfo')) {
            force_redirect(route('main.index'));
        }

        $courseId = $request->session()->get('courseId');
        $userInfo = $request->session()->get('userInfo');
        $groups = $request->session()->get('groups');
        $extraUserInfo = $request->session()->get('extraUserInfo');

        $feideId = $this->dataportenService->getFeideId($userInfo);

        info('Worker', [
            'FeideId' => $feideId,
        ]);

        $canvasUser = $this->canvasRepository->getUserByFeideId($feideId);
        $canvasCourse = $this->canvasRepository->getCourseById($courseId);

        info('Canvas course', (array)$canvasCourse);

        $groupCategories = $this->canvasRepository->getGroupCategories($courseId);

        return view('worker.index', [
            'courseId' => $courseId,
            'canvasUser' => $canvasUser,
            'userInfo' => $userInfo,
            'extraUserInfo' => $extraUserInfo,
            'groups' => $groups,
            'groupCategories' => $groupCategories,
        ]);
    }

    public function store(AddUserRequest $request)
    {
        $group = $request->input('group');
        $this->canvasRepository
                ->addUserToGroupInSection(
                                    (int)$group['user_id'],
                                    new GroupDto($group)
                                );

        return new SuccessResponse('Success');
    }


    protected function getGroup(Request $request)
    {
        return $request->session()->get('group');
    }

    protected function setGroup($group)
    {
        return $request->session()->put($group);
    }
}
