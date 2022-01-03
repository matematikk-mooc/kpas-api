@extends('layouts.app')


@section('content')
@if($hasDeservedDiploma)
    <div class="mainDiplomaPage diplomaBorder">
        <p class="diplomaTitle">DIPLOM</p>
        <p class="diplomaName">{{$diplomaName}}</p>
        <p class="diplomaDod">    har fullført kompetansepakken</p>
        <p class="diplomaDescription">{{$diplomaCourseName}}</p>
        <p class="diplomaIssuedBy">Kompetansepakken er levert av Utdanningsdirektoratet</p>
        <p class="diplomaIssuedPlace">Tromsø {{$diplomaDate}}</p> 
        <p class="diplomaCenter">
            @foreach ($logoList as $logo)
                <img class="diplomaIssuedByImage" alt="" src="images/{{$logo}}" title="{{$logo}}">
            @endforeach        
        </p>
    </div>
    <diploma-view>
    </diploma-view>
@else
    <no-diploma-view>
    </no-diploma-view>
@endif

@endsection

@section('scripts')

@if($downloadLinkOn)
<script>window.cookie = '{{ session()->getId() }}';</script>
@endif

@endsection
