<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;

class MainController extends Controller
{
    public function __construct()
    {}

    public function index(Request $request)
    {

        return view('main.index');
    }

    public function logout(Request $request)
    {
        $request->session()
                ->flush();

        redirect(trim(app('dataporten.api_url'),'/') . '/logout');
    }
}
