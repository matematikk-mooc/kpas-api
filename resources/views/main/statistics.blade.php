@extends('layouts.app')

@section('content')
<statistics-view></statistics-view>
@endsection

@section('scripts')
<script>window.cookie = '{{ session()->getId() }}';</script>
@endsection