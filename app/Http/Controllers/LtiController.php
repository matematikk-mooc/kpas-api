<?php

namespace App\Http\Controllers;

use App\LTI\Authenticator;
use App\LTI\CustomToolProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class LtiController extends Controller
{
    public function checkAuthorization(Request $request)
    {
        Authenticator::authenticate();

        return response()->redirectTo($request->has('path')
            ? $request->get('path')
            : route('main.index')
        );
    }
}
