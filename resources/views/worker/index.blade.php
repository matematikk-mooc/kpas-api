@extends('layouts.app')

@section('content')
<h1>Dataporten</h1>
<h3>Course ID: <span class="text-danger">{{ $courseId }}</span></h3>
<a href="{{ route('main.logout') }}" class="btn btn-primary float-right" style="margin-top:-80px;">Logout</a>
<hr/>
@include('common.dump', ['title' => 'Canvas user', 'data' => $canvasUser])
<hr/>
@include('common.dump', ['title' => 'Dataporten user info', 'data' => $userInfo])
<hr/>
@include('common.dump', ['title' => 'Dataporten groups info', 'data' => $groups])
<hr/>
@include('common.dump', ['title' => 'Dataporten extra user info', 'data' => $extraUserInfo])
@endsection
