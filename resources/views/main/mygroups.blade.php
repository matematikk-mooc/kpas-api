@extends('layouts.app')

@section('content')
<h1>Dataporten</h1>
<h3>Token</h3>
<div class="text-danger">{{ $token }}</div>

<hr/>
@include('main.partials.dump', ['title' => 'Dataporten user info', 'data' => $userInfo])
<hr/>
@include('main.partials.dump', ['title' => 'Dataporten groups info', 'data' => $groups])
<hr/>
@include('main.partials.dump', ['title' => 'Dataporten extra user info', 'data' => $extraUserInfo])

@endsection
