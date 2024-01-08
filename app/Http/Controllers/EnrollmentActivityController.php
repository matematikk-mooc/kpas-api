<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentActivity;
use Illuminate\Http\Request;

class EnrollmentActivityController extends Controller
{
    //
    public function index()
    {
        return EnrollmentActivity::all();
    }

    public function show(int $course_id, Request $request)
    {
        $enrollment = EnrollmentActivity::where('course_id', '=', $course_id);

        if ($request->has('from')) {
            $enrollment->where('activity_date', '>=', $request->from);
        }

        if ($request->has('to')) {
            $enrollment->where('activity_date', '<=', $request->to);
        }

        return $enrollment->get();
    }

    public function store(Request $request)
    {
        $UserActivity = EnrollmentActivity::create($request->all());

        return response()->json($UserActivity, 201);
    }

    public function delete(int $course_id)
    {

        $UserActivity = EnrollmentActivity::where('course_id', '=', $course_id)->first();
        $UserActivity->delete();
        return response()->json(null, 204);
    }
}
