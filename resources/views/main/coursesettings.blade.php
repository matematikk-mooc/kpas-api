@php
use App\Repositories\CourseSettingsRepository;
use App\Models\CourseSettings;
use App\Models\CourseFilter;
use App\Models\CourseCategory;
use App\Models\Filter;
use App\Models\Category;
use App\Models\CourseImage;
use App\Services\CanvasService;
use App\Repositories\CanvasRepository;
@endphp

@extends('layouts.app')

@section('content')
    @php
        $isadmin = false;

        $roles = $settings["custom_canvas_roles"];
        if (str_contains($roles, "Account Admin")) {
            $isadmin = true;
        }

        $course_id = intval($settings["custom_canvas_course_id"]);

        $courseSettingsRepository = new CourseSettingsRepository();
        $canvasRepository = new CanvasRepository(new CanvasService());

        $courseSettings = $courseSettingsRepository->getCourseSettings($course_id);
        $filters = $courseSettingsRepository->getFilters();
        $categories = $courseSettingsRepository->getCategories();
        $bannertypes = $courseSettingsRepository->getBannerTypes();
        $multilangtypes = $courseSettingsRepository->getMultilangTypes();
        $filtertypes = $courseSettingsRepository->getFilterTypes();
        $courseimages = $courseSettingsRepository->getCourseImages();
        $currenthighlighted = $courseSettingsRepository->getHighlightedCourse();
        $publicCourses = $canvasRepository->getPublicCourses();
    @endphp

<course-settings-view
                 :courseid="{{ json_encode($course_id) }}"
                 :filters="{{ json_encode($filters) }}"
                 :coursesettings="{{ json_encode($courseSettings) }}"
                 :categories="{{ json_encode($categories) }}"
                 :bannertypes="{{ json_encode($bannertypes) }}"
                 :multilangtypes="{{ json_encode($multilangtypes) }}"
                 :filtertypes="{{ json_encode($filtertypes) }}"
                 :isadmin= "{{ json_encode($isadmin) }}"
                 :courseimages = "{{ json_encode($courseimages) }}"
                 :publiccourses = "{{ json_encode($publicCourses) }}"
                 :currenthighlighted = "{{ json_encode($currenthighlighted) }}"></course-settings-view>

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
