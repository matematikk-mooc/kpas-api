@php
use App\Models\SurveyQuestion;
use App\Services\CanvasService;
use App\Repositories\CanvasRepository;
use App\Services\CanvasGraphQLService;
use App\Repositories\CanvasGraphQLRepository;
@endphp

@extends('layouts.app')

@section('content')
<div class="admin-dashboard-container">
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();
        $canvasService = new CanvasService();
        $canvasRepository = new CanvasRepository($canvasService);
        $course_id = intval($settings["custom_canvas_course_id"]);
        $courseModules = $canvasRepository->getCourseModules($course_id);
    @endphp

    <health-monitor-view id="health-view" :courseid="{{$course_id}}"></health-monitor-view>

    <activity-view id="activity-view" :courseid="{{$course_id}}"></activity-view>

    <admin-dashboard-view id="udir-view" :settings="{{ json_encode($settings) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}"
                 :coursemodules="{{ json_encode($courseModules) }}" >
    </admin-dashboard-view>

</div>
@endsection

@section('scripts')
    <script>
        window.cookie = '{{ session()->getId() }}';
    </script>
@endsection
