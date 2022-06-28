@extends('layouts.app')

@section('content')
<h1>Statistikk</h1>
Fyll inn statistikk her.

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
