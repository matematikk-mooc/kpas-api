<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\MatomoService;
use Illuminate\Http\Request;

class MatomoController extends Controller
{


    public function matomoStatistics(int $courseId, Request $request): SuccessResponse
    {
        logger("MatomoController.matomoStatistics");
        logger($courseId);
        $matomoService = new MatomoService();
        $data = $matomoService->getMatomoData($courseId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }


    public function webindex(int $courseId)
    {
        $matomoService = new MatomoService();
        $data = $matomoService->matomoData($courseId);
        return view('main.matomo')->withMatomoData($data);
    }
}
