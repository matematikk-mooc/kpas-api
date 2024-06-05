@extends('layouts.app')

@section('content')
<div>
    <div>
        <user-deletion-view>
        </user-deletion-view>
    </div>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
