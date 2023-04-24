@extends('layouts.app')


@section('content')

<no-cookies-view    
    state="{{$state}}" nonce="{{$nonce}}" targeturl="{{$targetUrl}}" storagetarget="{{$storageTarget}}" platformhost="{{$platformHost}}" ></no-cookies-view>


@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection

