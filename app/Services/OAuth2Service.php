<?php

namespace App\Services;

use League\OAuth2\Client\Provider\GenericProvider as OAuth2ClientProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuth2Service
{
    protected $provider;

    public function getAccessToken()
    {
        return $this->provider->getAccessToken();
    }

    public function getProvider(): OAuth2ClientProvider
    {
        try {
            return new OAuth2ClientProvider([
                    'clientId'                => config('dataporten.client_id'),
                    'clientSecret'            => config('dataporten.client_secret'),
                    'redirectUri'             => config('dataporten.redirect_uri'),
                    'urlAuthorize'            => dataporten_api_uri('oauth/authorization'),
                    'urlAccessToken'          => dataporten_api_uri('oauth/token'),
                    'verify'                  => false,
                ]);
        } catch (IdentityProviderException $e) {
            throw $e;
        }
    }
}
