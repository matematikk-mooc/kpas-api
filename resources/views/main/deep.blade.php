@extends('layouts.app')

@section('content')
<kpas-embed-view courseid="{{$courseId}}" appurl="{{ config('app.url') }}" diplomamode="{{$diplomaMode}}" statisticsmode="{{$statisticsMode}}" dashboardmode="{{$dashboardMode}}" surveyMode="{{$surveyMode}}" admindashboardmode="{{$adminDashboardMode}}" launchid="{{$id}}" configdirectory="{{$configDirectory}}">
</kpas-embed-view>


@endsection
@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>

@endsection

