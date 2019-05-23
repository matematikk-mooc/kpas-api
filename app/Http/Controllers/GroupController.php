<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Group\AddUserRequest;
use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasRepository;
use App\Services\DataportenService;

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

    public function __construct(DataportenService $dataportenService, CanvasRepository $canvasRepository)
    {
        $this->dataportenService = $dataportenService;
        $this->canvasRepository = $canvasRepository;
    }

    public function addUser(AddUserRequest $request): SuccessResponse
    {
        $group = new GroupDto(json_decode($request->input('group'), true));
        $unenrollForm = json_decode($request->input('unenrollFrom'));

//        $dataportenUserInfo = $this->dataportenService->getUserInfo();

        $feideId = 1; //$this->dataportenService->getFeideId($dataportenUserInfo);
        $canvasUser = $this->canvasRepository->getUserByFeideId($feideId);

        $this->canvasRepository->addUserToGroup($canvasUser->id, $group, $unenrollForm->unenrollmentIds);

        return new SuccessResponse(['']);
    }

    public function categories($groupId): SuccessResponse
    {
        $result = $this->canvasRepository->getGroupCategories($groupId);

        return new SuccessResponse($result);
    }
}
