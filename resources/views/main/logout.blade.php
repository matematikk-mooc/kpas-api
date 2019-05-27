@extends('layouts.app')

@section('content')
<div class="alert alert-info mb-4">
    Mangler course_id parameter.
</div>
<a href="{{ route('main.logout') }}" class="btn btn-primary">Logout</a>
@endsection
