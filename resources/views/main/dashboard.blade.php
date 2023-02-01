@php use App\Models\SurveyQuestion; @endphp
@extends('layouts.app')

@section('content')
    @php
        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();
    @endphp

    <dashboard-view :settings="{{ json_encode($settings) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}">
    </dashboard-view>

@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
