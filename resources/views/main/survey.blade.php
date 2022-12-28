@extends('layouts.app')

@section('content')


<h1>Survey blade</h1>

<h1>Tilgjengelige innstillinger</h1>
        @php
            var_dump($settings);
        @endphp

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
