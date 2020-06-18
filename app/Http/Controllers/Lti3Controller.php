<?php

namespace App\Http\Controllers;

use App\Exceptions\CanvasException;
use App\Exceptions\LtiException;
use App\Ltiv3\LTI3_Database;
use App\Services\CanvasService;
use GuzzleHttp\Client;
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
        try {
            $settings = $this->get_categories($settings);

        } catch (CanvasException $e) {
            throw new LtiException("Error at LTIv3 get categories from canvas :" . $e->getMessage());

        }

        $settings_new = [];
        foreach ($settings as $key => $value) {
            Arr::set($settings_new, 'settings.custom_' . $key, $value);
        }
        session(['settings' => $settings_new['settings']]);

        logger("Lti3Middleware has settings.");

        return view('lti.index');
    }

    /**
     *  Get categories for a given course from canvas api
     * @param $settings
     * @return array
     * @throws CanvasException
     */
    private function get_categories($settings)
    {
        $course = $settings["canvas_course_id"];
        $client = new Client();
        $canvas_service = new CanvasService($client);
        $categories = collect($canvas_service->getGroupCategories($course));
        foreach ($categories as $value) {
            if ($value->name == "Fylke") {
                $settings["county_category_id"] = (string)$value->id;
            } elseif ($value->name == "Skole") {
                $settings["institution_category_type"] = "school";
                $settings["institution_category_id"] = (string)$value->id;
            } elseif ($value->name == "Barnehage") {
                $settings["institution_category_type"] = "kindergarten";
                $settings["institution_category_id"] = (string)$value->id;
            } elseif ($value->name == "Kommune") {
                $settings["community_category_id"] = (string)$value->id;
            } elseif ($value->name == "Faggruppe i kommunen") {
                $settings["community_faculty_category_id"] = (string)$value->id;
            } elseif ($value->name == "Faggruppe i fylket") {
                $settings["county_faculty_category_id"] = (string)$value->id;
            } elseif ($value->name == "Leder/eier (fylke)") {
                $settings["county_principals_category_id"] = (string)$value->id;
            } elseif ($value->name == "Leder/eier (kommune") {
                $settings["community_principals_category_id"] = (string)$value->id;
            }
        }
        return $settings;

    }

}
