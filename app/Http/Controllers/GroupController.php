<?php

namespace App\Http\Controllers;

use App\Dto\GroupDto;
use App\Http\Requests\Group\AddUserRequest;
use App\Http\Requests\Group\GetCategoriesRequest;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Services\DataportenService;
use Symfony\Component\HttpFoundation\JsonResponse;

class GroupController extends Controller
{
    /**
     * @var DataportenService
     */
    protected $dataportenService;
    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(DataportenService $dataportenService, CanvasService $canvasService)
    {
        $this->dataportenService = $dataportenService;
        $this->canvasService = $canvasService;
    }

    public function addUser(AddUserRequest $request): SuccessResponse
    {
        $group = new GroupDto(json_decode($request->get('group'), true));
        $unenrollForm = json_decode($request->get('unenrollFrom'));

        $dataportenUserInfo = $this->dataportenService->getUserInfo();

        $feideId = $this->dataportenService->getFeideId($dataportenUserInfo);
        $canvasUser = $this->canvasService->getUserByFeideId($feideId);

        $result = $this->canvasService->addUserToGroup($canvasUser->id, $group, $unenrollForm->unenrollnmentIds);

        return new SuccessResponse($result);
    }

    public function categories($groupId): SuccessResponse
    {
        $result = $this->canvasService->getGroupCategories($groupId);

        return new SuccessResponse($result);
    }
}
