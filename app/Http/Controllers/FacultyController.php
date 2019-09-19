<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;

class FacultyController extends Controller
{
    public function index(): SuccessResponse
    {
        $settings = collect(session('settings'));

        $faculties = $settings->filter(function ($value, $key) {
            return strpos($key, 'faculty_option') !== false;
        });

        return new SuccessResponse($faculties->values()->toArray());
    }
}
