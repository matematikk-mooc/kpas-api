@extends('layouts.app')

@section('content')
<quiz-statistics-view></quiz-statistics-view>
<h1>Tilgjengelige innstillinger</h1>
        @php
            var_dump($settings);
        @endphp
@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection