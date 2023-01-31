<?php

namespace App\Http\Controllers;

use App\Exceptions\LtiException;
use App\Ltiv3\LTI3_Database;
use App\Services\CanvasService;
use App\Services\DiplomaService;
use App\Services\StatisticsService;
use App\Services\DashboardService;
use App\Services\SurveyService;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use IMSGlobal\LTI;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ErrorResponse;
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
        } catch (\Throwable $e) {
            throw new LtiException("Error at LTIv3 launch :" . $e->getMessage());
        }


        $diplomaMode = config('constants.options.DIPLOMA_MODE');
        $roleMode = config('constants.options.ROLE_GROUP_MODE');
        $statisticsMode = config('constants.options.STATISTICS_MODE');
        $dashboardMode = config('constants.options.DASHBOARD_MODE');
        $surveyMode = config('constants.options.SURVEY_MODE');
        $kpasMode = $request->query("kpasMode", $roleMode);
        if ($launch->is_resource_launch()) {
            logger('Resource Launch!');
        } else if ($launch->is_deep_link_launch()) {
            logger('Deep Linking Launch!');
            $settings = $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom'];

            $settings_new = [];
            foreach ($settings as $key => $value) {
                Arr::set($settings_new, 'settings.custom_' . $key, $value);
            }
            session(['settings' => $settings_new['settings']]);

            return view('main.deep')
            ->withCourseId($settings["canvas_course_id"])
            ->withSettings($settings_new)
            ->withId($launch->get_launch_id())
            ->withConfigDirectory($config_directory)
            ->withDiplomaMode($diplomaMode)
            ->withStatisticsMode($statisticsMode)
            ->withDashboardMode($dashboardMode)
            ->withSurveyMode($surveyMode)
            ->withRequest($request);
        } else {
            logger('Unknown launch type');
        }        

        $settings = $launch->get_launch_data()['https://purl.imsglobal.org/spec/lti/claim/custom'];
        logger("SETTINGS:" . print_r($settings, true));

        $settings["canvas_user_id"] = (string)$settings['canvas_user_id'];
        
        $kpasUserView = "group_management";
        if(isset($settings['kpas_user_view'])) {
            $kpasUserView = (string)$settings['kpas_user_view'];
        }

        if ($kpasUserView != 'user_management') {
            $settings["canvas_course_id"] = (string)$settings['canvas_course_id'];
            try {
                $settings = $this->get_categories($settings);

            } catch (\Throwable $e) {
                throw new LtiException("Error at LTIv3 get categories from canvas :" . $e->getMessage());
            }
        }

        $settings_new = [];
        foreach ($settings as $key => $value) {
            Arr::set($settings_new, 'settings.custom_' . $key, $value);
        }

        if ($kpasMode == $diplomaMode) {
            $noLogoList = [];
            $logoList = $request->query("logo", $noLogoList);
            Arr::set($settings_new, 'settings.custom_diploma_logo_list', $logoList);
        }
        session(['settings' => $settings_new['settings']]);

        logger("Lti3Middleware has settings.");

        $settings = session()->get('settings');  
        if ($kpasMode == $diplomaMode) {
            $downloadLink = true;
            logger("embed diploma");

            $diplomaService = new DiplomaService();
            $hasDeservedDiploma = $diplomaService->hasDeservedDiploma($settings);
            if(!$hasDeservedDiploma) {
                $downloadLink = false;
            }

            return $diplomaService->getDiplomaHtml($settings, $downloadLink, $hasDeservedDiploma);
        } 
        else if($kpasMode == $statisticsMode) {
            $statisticsService = new StatisticsService();
            return $statisticsService->getStatisticsHtml($settings);
        }
        else if($kpasMode == $dashboardMode) {
            $dashboardService = new DashboardService();
            return $dashboardService->getDashboardHtml($settings);
        }
        else if($kpasMode == $surveyMode){
            $surveyService = new SurveyService();
            return $surveyService->getSurveyBlade($settings);
        }

        if ($kpasUserView == 'user_management') {
            logger("Display user management view.");
            return view('usermerge.index');
        }
        return view('lti.index');
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

        } catch (\Throwable $e) {
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

    public function diplomaPdf(Request $request)
    {
        logger("Diploma");
        $settings = session()->get('settings');  
        logger($settings);

        $diplomaService = new DiplomaService();
        $hasDeservedDiploma = $diplomaService->hasDeservedDiploma($settings);
        if($hasDeservedDiploma) {
            $dompdf = new Dompdf();

            //To make images/ references work.
            $dompdf->getOptions()->setChroot(public_path());
    
            //To make external references to css etc. work.
            $dompdf->getOptions()->set('isRemoteEnabled', true);
            $downloadLink = false;
            $noLogoList = [];
            $logoList = $request->query("logo", $noLogoList);

            $html = $diplomaService->getDiplomaHtml($settings, $downloadLink, $hasDeservedDiploma);
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream("Diplom.pdf");
        } else {
            return (new ErrorResponse("Alle krav må fullføres før diplom kan lastes ned.", 403))->toResponse($request);
        }
    }
}
