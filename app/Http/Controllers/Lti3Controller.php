<?php

namespace App\Http\Controllers;

use App\Exceptions\LtiException;
use App\Ltiv3\LTI3_Database;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use IMSGlobal\LTI;

/**
 * @lti3 LTI v 1.3 request management
 *
 * APIs for managing users
 */
class Lti3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('lti3')->only('index');
    }

    /**
     * Check if the requesting platform is registered in our DB.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        logger("Lti3Controller.index");
        if (app()->environment('local')) {
            logger('A request from CANVAS', $request->all());
            logger('An user session', session('settings', []));
        }
        return view('lti.index');
    }

    /**
     * Launch requested link
     *
     * [ Authenticate and Redirect to the requested link ]
     * @param Request $request
     * @return Application|Factory|View
     * @throws LtiException
     */
    public function launch(Request $request)
    {
        $launch = LTI\LTI_Message_Launch::new(new LTI3_Database());
        try {
            $launch->validate();
        } catch (LTI\LTI_Exception $e) {
            throw new LtiException("Error at LTIv3 launch :" . $e->getMessage());
        }
        if ($launch->is_resource_launch()) {
            #session(['settings' => $launch->get_ags()]);

            return view('lti.index');
        } else if ($launch->is_deep_link_launch()) {
            echo 'Deep Linking Launch!';
        } else {
            echo 'Unknown launch type';
        }
        return view('lti.index');
    }

}
