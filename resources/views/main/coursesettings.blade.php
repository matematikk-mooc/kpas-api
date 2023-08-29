@php use App\Repositories\CourseSettingsRepository; use App\Models\CourseSettings; use App\Models\CourseFilter; use App\Models\CourseCategory; use App\Models\Filter; use App\Models\Category; @endphp
@extends('layouts.app')

@section('content')
    @php

        $course_id = intval($settings["custom_canvas_course_id"]);

        $courseSettingsRepository = new CourseSettingsRepository();

        $courseSettings = $courseSettingsRepository->getCourseSettings($course_id);
        $filters = $courseSettingsRepository->getFilters();
        $categories = $courseSettingsRepository->getCategories();
        $bannertypes = $courseSettingsRepository->getBannerTypes();
        $multilangtypes = $courseSettingsRepository->getMultilangTypes();
    @endphp

<course-settings-view
                 :courseid="{{ json_encode($course_id) }}"
                 :filters="{{ json_encode($filters) }}"
                 :coursesettings="{{ json_encode($courseSettings) }}"
                 :categories="{{ json_encode($categories) }}"
                 :bannertypes="{{ json_encode($bannertypes) }}"
                 :multilangtypes="{{ json_encode($multilangtypes) }}" ></course-settings-view>

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection