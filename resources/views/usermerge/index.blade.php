@extends('layouts.app')

@section('content')
<div>
    <div>
        <merge-user-view>
        </merge-user-view>
    </div>
</div>
@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
