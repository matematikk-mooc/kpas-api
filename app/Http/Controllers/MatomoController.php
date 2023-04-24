<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\MatomoService;
use Illuminate\Http\Request;

class MatomoController extends Controller
{


    public function matomoStatistics(int $courseId): SuccessResponse
    {
        logger("MatomoController.matomoStatistics");
        logger($courseId);
        $matomoService = new MatomoService();
        $data = $matomoService->getMatomoStatistics($courseId);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function getMatomoData(int $courseId, Request $request): SuccessResponse
    {
        $matomoService = new MatomoService();
        logger($request);
        $data = $matomoService->getMatomoStatistics($courseId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function webindex(int $courseId)
    {
        $matomoService = new MatomoService();
        $data = $matomoService->getMatomoStatistics2($courseId);
        return view('main.matomo')->withMatomoData($data);
    }
}
