@extends('layouts.app')

@section('content')
<kpas-embed-view appurl="{{ config('app.url') }}" diplomamode="{{$diplomaMode}}" statisticsmode="{{$statisticsMode}}" quizmode="{{$quizMode}}" surveymode="{{$surveyMode}}" launchid="{{$id}}" configdirectory="{{$configDirectory}}">
</kpas-embed-view>


@endsection
