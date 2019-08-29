<?php

namespace App\Http\Controllers;

use App\Services\DataportenService;
use App\Services\OAuth2Service;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
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

        try {
            $this->oauth2Service->setProvider();
            $oauth2Provider = $this->oauth2Service->getProvider();

            if (!$request->has('code')) {
                info('No code parameter in uri, redirect to the auth url', [
                    'fullUrl' => $request->fullUrl(),
                ]);
                force_redirect($oauth2Provider->getAuthorizationUrl());
            }

            $this->cacheDataportenData($request, $oauth2Provider);

            info('Store dataporten data', $this->getCachedDataportenData($request));

        } catch(IdentityProviderException $e) {
            info('IdentityProviderException exception', ['message' => $e->getMessage()]);
            force_redirect(route('main.index'));
        }

        if ($isMyGroups==true) {
            force_redirect(route('main.mygroups'));
        }

        force_redirect(route('worker.index'));
    }

    public function pageLogout()
    {
        return view('main.logout');
    }

    public function myGroups(Request $request)
    {
        if (!$request->session()->exists('minegrupper')) {
            force_redirect(route('main.index'));
        }

        return view('main.mygroups', $this->getCachedDataportenData($request));
    }

    public function logout(Request $request)
    {
        $request->session()
                ->flush();

        force_redirect(dataporten_api_uri('logout'));
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

    protected function handleCourseId(Request $request): void
    {
        if ($request->has('course_id')) {
            if(!is_numeric($request->input('course_id'))) {
                force_redirect(route('main.pageLogout'));
            }
            $courseId = (int)$request->input('course_id');
            $request->session()->put('courseId', $courseId);
        }
        if(!$request->session()->has('courseId')) {
            force_redirect(route('main.pageLogout'));
        }
    }

    protected function getToken($oauth2Provider, $options = [])
    {
        return $oauth2Provider->getAccessToken('authorization_code', $options)
                            ->getToken();
    }

    protected function cacheDataportenData(Request $request, $oauth2Provider): void
    {
        $authOptions = [
            'code' => $request->input('code'),
            'state' => $request->input('state'),
        ];
        $authorizationToken = $this->getToken($oauth2Provider, $authOptions);

        $this->dataportenService->setAccessKey($authorizationToken);
        $userInfo = $this->dataportenService->getUserInfo();
        $groupsInfo = $this->dataportenService->getGroupsInfo();
        $extraUserInfo = $this->dataportenService->getExtraUserInfo();

        $request->session()->put('token', $authorizationToken);
        $request->session()->put('userInfo', $userInfo);
        $request->session()->put('groups', $groupsInfo);
        $request->session()->put('extraUserInfo', $extraUserInfo);
    }

    protected function getCachedDataportenData(Request $request)
    {
        return [
            'token' => $request->session()->get('token'),
            'userInfo' => $request->session()->get('userInfo'),
            'groups' => $request->session()->get('groups'),
            'extraUserInfo' => $request->session()->get('extraUserInfo'),
        ];
    }
}
