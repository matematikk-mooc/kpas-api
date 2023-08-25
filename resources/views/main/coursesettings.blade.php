@php use App\Repositories\CourseSettingsRepository; use App\Models\CourseSettings; use App\Models\CourseFilter; use App\Models\CourseCategory; use App\Models\Filter; use App\Models\Category; @endphp
@extends('layouts.app')

@section('content')
    @php

        $course_id = intval($settings["custom_canvas_course_id"]);

        $courseSettingsRepository = new CourseSettingsRepository();

        $courseSettings = $courseSettingsRepository->getCourseSettings($course_id);
        $filters = $courseSettingsRepository->getFilters();
        $categories = $courseSettingsRepository->getCategories();
    @endphp

<h1>Filters</h1>
        @php
            var_dump($filters);
        @endphp

<h1>Categories</h1>
        @php
            var_dump($categories);
        @endphp

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection