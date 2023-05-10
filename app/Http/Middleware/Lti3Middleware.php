<?php

namespace App\Http\Middleware;

use App\Exceptions\LtiException;
use App\Ltiv3\LTI3_Database;
use Closure;
use Illuminate\Http\Request;
use IMSGlobal\LTI;

class Lti3Middleware
{
    /**
    * Handle LTI3 incoming request.
    *
    * @param Request $request
    * @param Closure $next
    * @return mixed
    * @throws LtiException
    */
    public function handle($request, Closure $next)
    {   
        $config_directory = $request->query("config_directory", "configs");
        logger("Middleware config directory:".$config_directory);
        logger("Target URL:".$request->input("target_link_uri"));

        try {
            $lti_oidc_login = LTI\LTI_OIDC_Login::new(new LTI3_Database($config_directory));
            $login = $lti_oidc_login->do_oidc_login_redirect($request->input("target_link_uri"), $request->toArray());
            $loginUrl = $login->get_redirect_url();
            $arr = parse_url($loginUrl);
            parse_str($arr['query'], $params); 
            logger($arr);

            return response(view('main.nocookies')->withState($params['state'])
            ->withNonce($params['nonce'])
            ->withTargetUrl($arr['query'])
            ->withStorageTarget("_parent")
            ->withPlatformHost($arr['host']));
            
        } catch (LTI\OIDC_Exception $e) {
            throw new LtiException("Exception at Lti3 controller : " . $e->getMessage());
        }
        
        return $next($request);
    }
}
