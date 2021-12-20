<?php

namespace App\Http\Controllers;

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
use App\Http\Responses\SuccessResponse;
use Dompdf\Dompdf;

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
        $config_directory = $request->query("config_directory", "configs");
        logger("LTI3Controller config directory:". $config_directory);
        $launch = LTI\LTI_Message_Launch::new(new LTI3_Database($config_directory));
        try {
            $launch->validate();
        } catch (\Exception $e) {
            throw new LtiException("Error at LTIv3 launch :" . $e->getMessage());
        }

        $diplomaMode = config('constants.options.DIPLOMA_MODE');
        $roleMode = config('constants.options.ROLE_GROUP_MODE');
        $kpasMode = $request->query("kpasMode", $roleMode);
        if ($launch->is_resource_launch()) {
            logger('Resource Launch!');
        } else if ($launch->is_deep_link_launch()) {
            logger('Deep Linking Launch!');
            return view('main.deep')->withId($launch->get_launch_id())->withConfigDirectory($config_directory)->withDiplomaMode($diplomaMode);
        } else {
            logger('Unknown launch type');
        }        

        $settings = $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom'];
        $settings["canvas_user_id"] = (string)$settings['canvas_user_id'];
        
        $kpasUserView = "group_management";
        if(isset($settings['kpas_user_view'])) {
            $kpasUserView = (string)$settings['kpas_user_view'];
        }

        if ($kpasUserView != 'user_management') {
            $settings["canvas_course_id"] = (string)$settings['canvas_course_id'];
            try {
                $settings = $this->get_categories($settings);

            } catch (\Exception $e) {
                throw new LtiException("Error at LTIv3 get categories from canvas :" . $e->getMessage());
            }
        }

        $settings_new = [];
        foreach ($settings as $key => $value) {
            Arr::set($settings_new, 'settings.custom_' . $key, $value);
        }
        session(['settings' => $settings_new['settings']]);

        logger("Lti3Middleware has settings.");

        if ($kpasMode == $diplomaMode) {
            $downloadLink = true;
            logger("embed diploma");
            $settings = session()->get('settings');  
            return $this->getDiplomaHtml($settings, $downloadLink);
        }

        if ($kpasUserView == 'user_management') {
            logger("Display user management view.");
            return view('usermerge.index');
        }
        return view('lti.index');
    }

    private function getDiplomaHtml($settings,$downloadLink) {
        logger("getDiplomaHtml");
        logger($settings);
        $diplomaDisplayName = $settings['custom_canvas_user_display_name'];
        $diplomaCourseName = $settings['custom_canvas_course_name'];
        $diplomaDate=date("d.m.Y") ;
    
        return view('main.diploma')->withDiplomaName($diplomaDisplayName)->withDiplomaCourseName($diplomaCourseName)->withDiplomaDate($diplomaDate)->withDownloadLinkOn($downloadLink);
    }
    /**
     *  Get categories for a given course from canvas api
     * @param $settings
     * @return array
     * @throws LtiException
     */
    private function get_categories($settings)
    {
        $course = $settings["canvas_course_id"];
        $client = new Client();
        $canvas_service = new CanvasService($client);
        try {
            $categories = collect($canvas_service->getGroupCategories($course));

        } catch (\Exception $e) {
            throw new LtiException("Error at LTIv3 get categories from canvas :" . $e->getMessage());
        }

        logger("get_categories: " . print_r($categories, true));
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
            } elseif ($value->name == "Faggruppe kommune") {
                $settings["community_faculty_category_id"] = (string)$value->id;
            } elseif ($value->name == "Faggruppe fylke") {
                $settings["county_faculty_category_id"] = (string)$value->id;
            } elseif ($value->name == "Faggruppe nasjonalt") {
                $settings["national_faculty_category_id"] = (string)$value->id;
            } elseif ($value->name == "Leder/eier (fylke)") {
                $settings["county_principals_category_id"] = (string)$value->id;
            } elseif ($value->name == "Leder/eier (kommune)") {
                $settings["community_principals_category_id"] = (string)$value->id;
            }
        }
        return $settings;

    }

    public function institution()
    {
        logger("===========");
        logger("INSTITUTION");
        logger("===========");
        logger(print_r(session()->get('settings'), true));
        $customInstitutionType = Arr::get(session()->get('settings'), 'custom_institution_category_type');
        $customInstitutionLeaderDescription = Arr::get(session()->get('settings'), 'custom_institution_leader_description');
        $customInstitutionParticipantDescription = Arr::get(session()->get('settings'), 'custom_institution_participant_description');

        logger($customInstitutionParticipantDescription);
        logger($customInstitutionLeaderDescription);

        $institution["institutionType"] = $customInstitutionType;
        $institution["institutionLeaderDescription"] = $customInstitutionLeaderDescription ? $customInstitutionLeaderDescription : "Leder/eier";
        $institution["institutionParticipantDescription"] = $customInstitutionParticipantDescription ? $customInstitutionParticipantDescription : "Deltager";
        logger(print_r($institution, true));

        return new SuccessResponse($institution);
    }

    public function kpas_settings()
    {
        logger("===========");
        logger("SETTINGS");
        logger("===========");
        $customDeep = Arr::get(session()->get('settings'), 'custom_deep');
        logger("Deep: " . $customDeep);

        $kpasSettings["deep"] = $customDeep ? $customDeep : false;

        return new SuccessResponse($kpasSettings);
    }

    public function diploma(Request $request)
    {
        logger("Diploma");
        $settings = session('settings');
        logger($settings);
        $dompdf = new Dompdf();
        $dompdf->getOptions()->setChroot(public_path());
        $dompdf->getOptions()->set('isRemoteEnabled', true);
        $downloadLink = false;
        $html = $this->getDiplomaHtml($settings, $downloadLink);
        logger($html);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("Diplom.pdf");
    }
}
