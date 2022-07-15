@extends('layouts.app')

@section('content')
<statistics-view>
</statistics-view>

<h1>Statistikk blade</h1>

Fyll inn statistikk her:
        @php
            var_dump($statistics);
        @endphp

<h1>Tilgjengelige innstillinger</h1>
        @php
            var_dump($settings);
        @endphp

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
