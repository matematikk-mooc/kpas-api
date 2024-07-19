<?php

namespace App\Repositories;

use App\Services\CanvasGraphQLService;

class CanvasGraphQLRepository
{
    /**
     * @var CanvasGraphQLService
     */
    protected $canvasGraphQLService;

    public function __construct(CanvasGraphQLService $canvasGraphQLServiceService)
    {
        $this->canvasGraphQLService = $canvasGraphQLServiceService;
    }


    public function modulesConnection(int $courseId){
        $result = $this->canvasGraphQLService->modulesConnection($courseId);
        return $result;
    }
}