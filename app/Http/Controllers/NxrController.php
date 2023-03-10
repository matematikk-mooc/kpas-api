<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\DataNsrService;
use Illuminate\Http\Request;

class NxrController extends Controller
{
    protected $dataNsrService;

    public function __construct(DataNsrService $dataNsrService)
    {
        $this->dataNsrService = $dataNsrService;
    }

    public function getSchool($orgNr)
    {
        $data = $this->dataNsrService->getSchoolByOrgNr($orgNr);
        return new SuccessResponse($data);
    }

    public function getKindergarten($orgNr){
        $data = $this->dataNsrService->getKindergartenByOrgNr($orgNr);
        return new SuccessResponse($data);
    }

}