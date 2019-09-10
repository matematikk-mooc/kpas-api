<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\DataNsrService;

class SchoolsController extends Controller
{
    /**
     * @var DataNsrService
     */
    protected $dataNsrService;

    public function __construct(DataNsrService $dataNsrService)
    {
        $this->dataNsrService = $dataNsrService;
    }

    public function counties(): SuccessResponse
    {
        $counties = $this->dataNsrService->getCounties();

        return new SuccessResponse($counties);
    }

    public function communities(string $countyId): SuccessResponse
    {
        $communities = $this->dataNsrService->getCommunities($countyId);

        return new SuccessResponse($communities);
    }

    public function schools(string $communityId): SuccessResponse
    {
        $schools = $this->dataNsrService->getSchools($communityId);

        return new SuccessResponse($schools);
    }
}
