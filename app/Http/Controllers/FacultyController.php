<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Arr;

class FacultyController extends Controller
{
    public function index(): SuccessResponse
    {
        $settings = collect(session('settings'));
        if (session()->has('settings') &&
            Arr::has(session()->get('settings', []), [
                'custom_county_faculty_category_id',
                'custom_community_faculty_category_id',])) {
            $faculties = $settings->filter(function ($value, $key) {
                return strpos($key, 'faculty_option') !== false;
            });
            return new SuccessResponse($faculties->values()->toArray());
        }
        return new SuccessResponse([]);
    }
}
