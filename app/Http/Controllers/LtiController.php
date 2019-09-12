<?php

namespace App\Http\Controllers;

use App\LTI\Authenticator;
use App\LTI\CustomToolProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;

class LtiController extends Controller
{
    public function __construct()
    {
        $this->middleware('lti')->only('index');
    }

    public function index(Request $request)
    {
        if(app()->environment('local')) {
            logger('A request from CANVAS', $request->all());
            logger('An user session', session('settings', []));
        }
        return view('lti.index');
    }
}
