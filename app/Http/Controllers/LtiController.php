<?php

namespace App\Http\Controllers;

use App\LTI\CustomToolProvider;
use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class LtiController extends Controller
{
    public function checkAuthorization()
    {
        $db = DB::connection()->getPdo();
        $dataConnector = DataConnector::getDataConnector('', $db, 'pdo');
        $toolProvider = new CustomToolProvider($dataConnector);
        $toolProvider->handleRequest();

        return response()->redirectTo(route('main.index'));
    }
}
