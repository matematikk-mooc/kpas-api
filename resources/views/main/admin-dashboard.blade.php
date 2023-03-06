@php use App\Models\SurveyQuestion; use App\Services\CanvasService; use App\Repositories\CanvasRepository; use GuzzleHttp\Client; @endphp
@extends('layouts.app')

@section('content')
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();
        $canvasService = new CanvasService(new Client());
        $canvasRepository = new CanvasRepository($canvasService);
        $course_id = intval($settings["custom_canvas_course_id"]);
        $courseModules = $canvasRepository->getCourseModules($course_id);
    @endphp

    <admin-dashboard-view :settings="{{ json_encode($settings) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}"
                 :coursemodules="{{ json_encode($courseModules) }}" >
    </admin-dashboard-view>

@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection