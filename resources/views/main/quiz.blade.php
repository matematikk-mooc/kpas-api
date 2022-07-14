@extends('layouts.app')

@section('content')
<h1>Quiz</h1>
@include('common.dump', ['title' => 'Quizdata', 'data' => $quizData])
@endsection
