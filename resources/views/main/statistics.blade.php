@extends('layouts.app')

@section('content')
<h1>Statistikk</h1>
Fyll inn statistikk her.
{{$statistics}}

<h1>Tilgjengelige innstillinger</h1>
{{$settings}}

@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
