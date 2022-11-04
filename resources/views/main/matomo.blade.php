@extends('layouts.app')

@section('content')
<h1> MatomoData </h1>
@include('common.dump', ['title' => 'MatomoData', 'data' => $matomoData])
@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection