<?php

namespace App\LTI;

use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class Authenticator
{
    public static function authenticate()
    {
        $db = DB::connection()->getPdo();
        $dataConnector = DataConnector::getDataConnector('', $db, 'pdo');
        $toolProvider = new CustomToolProvider($dataConnector);
        $toolProvider->handleRequest();
    }
}
