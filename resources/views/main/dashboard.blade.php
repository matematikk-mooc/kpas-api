@php
use App\Models\SurveyQuestion;
use App\Services\CanvasService;
use App\Repositories\CanvasRepository;
@endphp

@extends('layouts.app')

@section('content')
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();

        $canvasService = new CanvasService();
        $canvasRepository = new CanvasRepository($canvasService);
        $courseModules = $courseId != NULL ? $canvasRepository->getCourseModules($courseId) : [];
    @endphp

    <dashboard-view
        :settings="{{ json_encode($settings) }}"
        :likert5ops="{{ json_encode($likertScale5ptOptions) }}"
        :coursemodules="{{ json_encode($courseModules) }}"
    />
@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
