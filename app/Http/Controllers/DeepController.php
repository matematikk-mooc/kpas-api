<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use EasyRdf;
use IMSGlobal\LTI;
use App\Ltiv3\LTI3_Database;

class DeepController extends Controller
{
    public function index()
    {
        $config_directory = "configs";
        if(isset($_REQUEST["config_directory"])) {
            $config_directory = $_REQUEST["config_directory"];
        }
        logger("DeepController config directory:". $config_directory);
        $launch = LTI\LTI_Message_launch::from_cache($_REQUEST['launch_id'], new LTI3_Database($config_directory));
        $dl = $launch->get_deep_link();
        $url = config('app.url');
        $resource = LTI\LTI_Deep_Link_Resource::new()
        ->set_url($url . "/launch?config_directory=" . $config_directory)
        ->set_custom_params(['deep' => true])
        ->set_title('KPAS')
        ->set_target('iframe'); 
        $dl->output_response_form([$resource]);    
    }    
}

