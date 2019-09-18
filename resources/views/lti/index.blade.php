@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <group-enroll-view>
        </group-enroll-view>
    </div>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ request()->cookie(config('session.cookie')) }}';</script>
@endsection
