@php use App\Models\SurveyQuestion; use App\Services\CanvasService; use App\Repositories\CanvasRepository; use GuzzleHttp\Client; use App\Services\CanvasGraphQLService; use App\Repositories\CanvasGraphQLRepository; @endphp
@extends('layouts.app')

@section('content')
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();
        $canvasService = new CanvasService(new Client());
        $canvasRepository = new CanvasRepository($canvasService);
        $course_id = intval($settings["custom_canvas_course_id"]);
        $courseModules = $canvasRepository->getCourseModules($course_id);
        $canvasGraphQLService = new CanvasGraphQLService();
        $canvasGraphQLRepository = new CanvasGraphQLRepository($canvasGraphQLService);
        $modulesItems = $canvasGraphQLRepository->modulesConnection($course_id);

    @endphp

    <button id="dashboard-view-switch" type="button" class="btn btn-primary" style="float: right;" onclick="toggle()">
        Helsesjekk
    </button>

    <admin-dashboard-view id="udir-view" :settings="{{ json_encode($settings) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}"
                 :coursemodules="{{ json_encode($courseModules) }}" >
    </admin-dashboard-view>

    <health-monitor-view id="health-view" :courseid="{{$course_id}}" :moduleitems="{{json_encode($modulesItems)}}"></health-monitor-view>

@endsection

@section('scripts')
    <script>
        window.cookie = '{{ session()->getId() }}';
        var health_view = false;
        toggle(health_view);
        function toggle() {
            health_view? health_view = false : health_view = true;
            toggleViews(health_view);
            updateButtonText(health_view);
         }
        function toggleViews(health_view) {
            var adminDashboardView = document.querySelector('#udir-view');
            var healthCheckView = document.querySelector('#health-view');

            if (health_view) {
                adminDashboardView.setAttribute('style', 'display: none');
                healthCheckView.setAttribute('style', 'display: block');
            } else {
                adminDashboardView.setAttribute('style', 'display: block');
                healthCheckView.setAttribute('style', 'display: none');
            }
        }
        function updateButtonText(health_view) {
            var button = document.querySelector('#dashboard-view-switch');
            button.textContent = health_view ? 'Udir Dashboard' : 'Helsesjekk';
        }
    </script>
@endsection
