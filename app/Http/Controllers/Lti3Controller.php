<?php

namespace App\Http\Controllers;

use App\Exceptions\LtiException;
use App\Ltiv3\LTI3_Database;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        } catch (\Exception $e) {
            throw new LtiException("Error at LTIv3 launch :" . $e->getMessage());
        }

        $settings = $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom'];
        $settings["canvas_user_id"] = (string)$settings['canvas_user_id'];
        $settings["canvas_course_id"] = (string)$settings['canvas_course_id'];
        $settings["canvas_user_login_id"] = $launch->get_launch_data()['email'];

        $settings_new = [];

        foreach ($settings as $key => $value) {
            Arr::set($settings_new, 'settings.custom_' . $key, $value);
        }
        session(['settings' => $settings_new['settings']]);

        logger("Lti3Middleware has settings.");

        $this->parse_token($launch);
        if ($launch->is_resource_launch()) {
            return view('lti.index');
        } else if ($launch->is_deep_link_launch()) {
            echo 'Deep Linking Launch!';
        } else {
            echo 'Unknown launch type';
        }
        return view('lti.index');
    }

    /*
     *  Parse id_token
     * */
    protected function parse_token($launch)
    {
        $launch->get_launch_data();

        logger("Lti3Middleware has settings.");
    }

}
