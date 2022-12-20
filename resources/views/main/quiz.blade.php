
@extends('layouts.app')

@section('content')
@if(isset($settings))
    <quiz-statistics-view :settings="{{ json_encode($settings) }}"></quiz-statistics-view>
@else
    <quiz-statistics-view></quiz-statistics-view>
@endif

<!-- <h1>Tilgjengelige innstillinger</h1>
@if(isset($settings))
@php
var_dump($settings);
@endphp
@endif -->

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
