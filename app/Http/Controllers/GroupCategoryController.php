<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Repositories\CanvasDbRepository;

class GroupCategoryController extends Controller
{
    /**
     * @var CanvasDbRepository
     */
    private $canvasDbRepository;

    public function __construct(CanvasDbRepository $canvasDbRepository)
    {
        $this->canvasDbRepository = $canvasDbRepository;
    }

    public function index($groupId): SuccessResponse
    {
        $result = $this->canvasDbRepository->getGroupCategories($groupId);

        return new SuccessResponse($result);
    }
}
