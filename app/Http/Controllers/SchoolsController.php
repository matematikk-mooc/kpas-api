<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\DataNsrService;
use Illuminate\Support\Str;

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

        return new SuccessResponse($this->sortByName($counties));
    }

    public function communities(string $countyId): SuccessResponse
    {
        $communities = $this->dataNsrService->getCommunities($countyId);

        return new SuccessResponse($this->sortByName($communities));
    }

    public function schools(string $communityId): SuccessResponse
    {
        $schools = $this->dataNsrService->getSchools($communityId);

        $schools = $this->sortByName($schools);

        $schools = collect($schools)
            ->filter(function ($school) {
                return $this->isSchool($school);
            })
            ->unique('Navn')
            ->values()
            ->toArray();

        return new SuccessResponse($schools);
    }

    protected function sortByName(array $data, string $key = 'Navn'): array
    {
        return collect($data)->sortBy($key)->values()->toArray();
    }

    protected function isSchool($school): bool
    {
        return Str::startsWith($school->NaceKode1, '85') ||
            Str::startsWith($school->NaceKode2, '85') ||
            Str::startsWith($school->NaceKode3, '85');
    }
}
