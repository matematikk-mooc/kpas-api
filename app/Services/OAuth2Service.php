<?php

namespace App\Services;

use League\OAuth2\Client\Provider\GenericProvider as OAuth2ClientProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuth2Service
{
    protected $provider;

    public function setProvider()
    {
        try {
            $this->provider = new OAuth2ClientProvider([
                    'clientId'                => config('dataporten.client_id'),
                    'clientSecret'            => config('dataporten.client_secret'),
                    'redirectUri'             => config('dataporten.redirect_uri'),
                    'urlAuthorize'            => dataporten_auth_uri('oauth/authorization'),
                    'urlAccessToken'          => dataporten_auth_uri('oauth/token'),
                    'urlResourceOwnerDetails' => dataporten_auth_uri('userinfo'),
                    'verify'                  => false,
                ]);
        } catch (IdentityProviderException $e) {
            throw $e;
        }
    }

    public function getProvider()
    {
        return $this->provider;
    }
}
