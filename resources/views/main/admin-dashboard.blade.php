@php use App\Models\SurveyQuestion; @endphp
@extends('layouts.app')

@section('content')
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();
    @endphp

    <admin-dashboard-view :settings="{{ json_encode($settings) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}">
    </admin-dashboard-view>

@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection