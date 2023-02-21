@php use App\Services\CanvasService; use App\Repositories\CanvasRepository; use GuzzleHttp\Client; @endphp
@extends('layouts.app')

@section('content')

    @php
        $canvasService = new CanvasService(new Client());
        $canvasRepository = new CanvasRepository($canvasService);
        $courseModules = $canvasRepository->getCourseModules($courseId);
    @endphp

<kpas-embed-view    
    courseid="{{$courseId}}" 
    :coursemodules="{{ json_encode($courseModules) }}" 
    appurl="{{ config('app.url') }}" 
    diplomamode="{{$diplomaMode}}" 
    statisticsmode="{{$statisticsMode}}" 
    dashboardmode="{{$dashboardMode}}" 
    surveyMode="{{$surveyMode}}"
    admindashboardmode="{{$adminDashboardMode}}" 
    launchid="{{$id}}" 
    configdirectory="{{$configDirectory}}">

</kpas-embed-view>


@endsection
@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>

@endsection

