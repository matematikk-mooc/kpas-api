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

        $isMyGroups = $this->handleMyGroups($request);

        if($isMyGroups==false) {
            $this->handleCourseId($request);
        }

        return view('main.index');
    }

    public function pageLogout()
    {
        return view('main.logout');
    }

    public function logout(Request $request)
    {
        $request->session()
                ->flush();

        redirect(dataporten_api_uri('logout'));
    }

    protected function handleMyGroups(Request $request): bool
    {
        $isMyGroups = $request->session()->exists('minegrupper');

        if($isMyGroups == false && $request->has('minegrupper')) {
            $isMyGroups = true;
            $request->session()->put('minegrupper', $isMyGroups);
        }
        return $isMyGroups;
    }

    protected function handleCourseId(Request $request)
    {
        if(!$request->has('course_id') || !is_numeric($request->input('course_id'))) {
            force_redirect(route('main.pageLogout'));
        }

        $courseId = (int)$request->input('course_id');
        $request->session()->put('courseId', $courseId);
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
