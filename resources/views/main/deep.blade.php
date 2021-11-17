@extends('layouts.app')

@section('content')
<h1><a href="{{ config('app.url') }}/deep?launch_id={{$id}}&config_directory={{$configDirectory}}">Sett inn Rolle- og gruppeverktøy</a></h1>
@endsection
