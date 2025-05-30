<?php

namespace App\Ltiv3;

use App\Exceptions\LtiException;
use App\Utils\SentryTrace;
use IMSGlobal\LTI;

class LTI3_Database implements LTI\Database
{
    public function __construct($config_directory)
    {
        session()->start();
        $_SESSION['iss'] = [];

        try {
            $reg_configs = array_diff(scandir(database_path($config_directory)), ['..', '.', '.DS_Store']);

            foreach ($reg_configs as $key => $reg_config) { 
                $config_path = database_path($config_directory) . "/$reg_config";
                $config_data = SentryTrace::fileGetContents($config_path);

                $_SESSION['iss'] = array_merge($_SESSION['iss'], json_decode($config_data, true));
            }
        } catch (\Throwable $e) {
            throw new LtiException("LTI v1.3 configuration error: " . $e->getMessage());
        }
    }

    public function find_registration_by_issuer($iss)
    {
        if (empty($_SESSION['iss']) || empty($_SESSION['iss'][$iss])) {
            return false;
        }
        try {
            $registration = LTI\LTI_Registration::new()
                ->set_auth_login_url($_SESSION['iss'][$iss]['auth_login_url'])
                ->set_auth_token_url($_SESSION['iss'][$iss]['auth_token_url'])
                #->set_auth_server($_SESSION['iss'][$iss]['auth_server'])
                ->set_client_id($_SESSION['iss'][$iss]['client_id'])
                ->set_key_set_url($_SESSION['iss'][$iss]['key_set_url'])
                ->set_kid($_SESSION['iss'][$iss]['kid'])
                ->set_issuer($iss)
                ->set_tool_private_key($this->private_key($iss));

        } catch (\Throwable $e) {
            throw new LtiException("The platform is not registered : " . $e->getMessage());
        }

        return $registration;
    }

    public function find_deployment($iss, $deployment_id)
    {
        logger($deployment_id);
        logger($_SESSION['iss'][$iss]['deployment']);
        if (!in_array($deployment_id, $_SESSION['iss'][$iss]['deployment'])) {
            return false;
        }
        return LTI\LTI_Deployment::new()
            ->set_deployment_id($deployment_id);
    }

    private function private_key($iss)
    {
        $privateKeyPath = __DIR__ . $_SESSION['iss'][$iss]['private_key_file'];
        return SentryTrace::fileGetContents($privateKeyPath);
    }
}
