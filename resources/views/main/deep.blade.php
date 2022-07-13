@extends('layouts.app')

@section('content')
<kpas-embed-view appurl="{{ config('app.url') }}" diplomamode="{{$diplomaMode}}" statisticsmode="{{$statisticsMode}}" launchid="{{$id}}" configdirectory="{{$configDirectory}}">
</kpas-embed-view>


@endsection
