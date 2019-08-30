<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Group\AddUserRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasRepository;
use App\Repositories\CanvasDbRepository;
use App\Services\DataportenService;
use Illuminate\Support\Arr;

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
}
