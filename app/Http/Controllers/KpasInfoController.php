<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;

class KpasInfoController extends Controller
{
    public function index()
    {
        $kpasinfo["privacyPolicyVersion"] = "1.0";
        return new SuccessResponse($kpasinfo);
    }
}
