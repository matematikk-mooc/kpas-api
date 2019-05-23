<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\DataportenService;
use App\Services\OAuth2Service;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @var DataportenService
     */
    protected $dataportenService;

    /**
     * @var OAuth2Service
     */
    protected $oauth2Service;

    public function __construct(DataportenService $dataportenService, OAuth2Service $oauth2Service)
    {
        $this->dataportenService = $dataportenService;
        $this->oauth2Service = $oauth2Service;
    }

    public function index(Request $request)
    {
        $isMyGroups = $this->handleMyGroups($request);

        if ($isMyGroups==false) {
            $this->handleCourseId($request);
        }

        $oauth2Provider = $this->oauth2Service->getProvider();

        if (!$request->has('code')) {
            force_redirect($oauth2Provider->getAuthorizationUrl());
        }

        $this->cacheDataportenData();

        if ($isMyGroups==true) {
            force_redirect(route('main.mygroups'));
        }

        force_redirect(route('main.worker'));
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

        if ($isMyGroups == false && $request->has('minegrupper')) {
            $isMyGroups = true;
            $request->session()->put('minegrupper', $isMyGroups);
        }
        return $isMyGroups;
    }

    protected function handleCourseId(Request $request)
    {
        if (!$request->has('course_id') || !is_numeric($request->input('course_id'))) {
            force_redirect(route('main.pageLogout'));
        }

        $courseId = (int)$request->input('course_id');
        $request->session()->put('courseId', $courseId);
    }

    protected function getToken(Request $request, $oauth2Provider)
    {
        return $oauth2Provider->provider->getAccessToken('authorization_code', [
            'code' => $request->input('code'),
            'state' => $request->input('state')
        ])->getToken();
    }

    protected function cacheDataportenData($oauth2Provider): void
    {
        $authorizationToken = $this->getAccessToken($oauth2Provider);

        $this->dataportenService->setAccessKey($authorizationToken);
        $userInfo = $this->dataportenService->getUserInfo();
        $groupsInfo = $this->dataportenService->getGroupsInfo();
        $extraUserInfo = $this->dataportenService->getExtraUserInfo();

        $request->session()->put('token', $authorizationToken);
        $request->session()->put('userInfo', $userInfo);
        $request->session()->put('groups', $groupsInfo);
        $request->session()->put('extraUserInfo', $extraUserInfo);
    }
}
