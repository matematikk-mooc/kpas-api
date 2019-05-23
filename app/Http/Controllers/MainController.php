<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\DataportenService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @var DataportenService
     */
    protected $dataportenService;

    public function __construct(DataportenService $dataportenService)
    {
        $this->dataportenService = $dataportenService;
    }

    public function index(Request $request)
    {

        return view('main.index');
    }

    public function logout(Request $request)
    {
        $request->session()
                ->flush();

        redirect(dataporten_api_url() . '/logout');
    }

    protected function getAccessToken(): string
    {

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => config('dataporten.client_id'),
                'clientSecret'            => config('dataporten.client_secret'),
                'redirectUri'             => config('dataporten.redirect_uri'),
                'urlAuthorize'            => dataporten_api_uri('oauth/authorization'),
                'urlAccessToken'          => dataporten_api_uri('oauth/token'),
                'verify'                  => false,
            ]);

        try {
            return $provider->getAccessToken('client_credentials');
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            throw $e;
        }
    }
}
