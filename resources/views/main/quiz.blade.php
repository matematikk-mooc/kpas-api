
@extends('layouts.app')

@section('content')
<h1>Quiz blade</h1>
<h1>Tilgjengelige innstillinger</h1>

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection