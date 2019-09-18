@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <group-enroll-view>
            v-if="window.cookie">
        </group-enroll-view>
    </div>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ request()->cookie(config('session.cookie')) }}';</script>
@endsection
