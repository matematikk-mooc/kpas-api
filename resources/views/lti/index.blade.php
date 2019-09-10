@extends('layouts.app')

@section('content')
<div class="alert alert-info mb-4">
    <group-enroll-view
        v-if="window.cookie"
    ></group-enroll-view>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ request()->cookie(config('session.cookie')) }}';</script>
@endsection
