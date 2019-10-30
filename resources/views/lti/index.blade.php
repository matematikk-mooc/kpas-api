@extends('layouts.app')

@section('content')
<div>
    <div>
        <group-enroll-view>
        </group-enroll-view>
    </div>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
