@extends('layouts.app')


@section('content')
<div class="main-page diplomaBorder">
    <p class="diplomaTitle">DIPLOM</p>
    <p class="diplomaName">{{$diplomaName}}</p>
    <p class="diplomaDod">    har fullført kompetansepakken</p>
    <p class="diplomaDescription">{{$diplomaCourseName}}</p>
    <p class="diplomaIssuedBy">Kompetansepakken er levert av Utdanningsdirektoratet</p>
    <p class="diplomaIssuedPlace">Tromsø {{$diplomaDate}}</p> 
    <p class="diplomaCenter">
        <img class="diplomaIssuedByImage" alt="" src="images/image1.png" title="Utdanningsdirektoratet">
    </p>
</div>
<diploma-view>
</diploma-view>

@endsection

@if($downloadLinkOn)
<script>window.cookie = '{{ session()->getId() }}';</script>
@endif
