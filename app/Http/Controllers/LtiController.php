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
        logger("LtiController::index");
        if(app()->environment('local')) {
            logger('LtiController::index message=A request from CANVAS', $request->all());
            logger('LtiController::index message=An user session', session('settings', []));
        }
        return view('lti.index');
    }
    public function institution_type(){
        return session('settings.custom_institution_category_type');
    }
}
