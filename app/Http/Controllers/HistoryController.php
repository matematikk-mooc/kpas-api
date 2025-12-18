<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{


    public function getUserHistoryData(int $userId, Request $request): SuccessResponse
    {
        $historyService = new HistoryService();
        $data = $historyService->getUserHistory($userId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function getUserContextHistoryData(int $userId, int $contextId, Request $request): SuccessResponse
    {
        $historyService = new HistoryService();
        $data = $historyService->getUserContextHistory($userId, $contextId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }

    public function getContextHistoryData(int $contextId, Request $request): SuccessResponse
    {
        $historyService = new HistoryService();
        $data = $historyService->getContextHistory($contextId, $request->from, $request->to);
        $res = $data->getBody()->getContents();
        return new SuccessResponse($res);
    }


}
