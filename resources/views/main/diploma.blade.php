@extends('layouts.app')


@section('content')
@if($hasDeservedDiploma)
    <div class="mainDiplomaPage diplomaBorder">
            <div class="diplomaTitle diplomaCenter">DIPLOM</div>
            <div class="diplomaName diplomaCenter">{{$diplomaName}}</div>
            <div class="diplomaDod diplomaCenter">har fullført kompetansepakken</div>
            <div class="diplomaCourseName diplomaCenter">{{$diplomaCourseName}}</div>
            <div class="diplomaDescription diplomaCenter">{{$diplomaCourseDescription}}</div>
            <div class="diplomaIssuedBy diplomaCenter">Kompetansepakken er levert av Utdanningsdirektoratet</div>
            <div class="diplomaIssuedPlace diplomaCenter">Tromsø {{$diplomaDate}}</div> 
            <div class="diplomaLogos diplomaCenter">
                @foreach ($logoList as $logo)
                    <img class="diplomaIssuedByImage" alt="" src="images/{{$logo}}" title="{{$logo}}">
                @endforeach        
            </div>
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
