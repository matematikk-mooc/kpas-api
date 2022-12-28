@extends('layouts.app')

@section('content')
<kpas-embed-view courseid="{{$courseId}}" appurl="{{ config('app.url') }}" diplomamode="{{$diplomaMode}}" statisticsmode="{{$statisticsMode}}" quizmode="{{$quizMode}}" surveyMode="{{$surveyMode}}" launchid="{{$id}}" configdirectory="{{$configDirectory}}">
</kpas-embed-view>


@endsection
