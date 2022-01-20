@extends('layouts.app')


@section('content')
@if($hasDeservedDiploma)
    <div class="mainDiplomaPage diplomaBorder">
        <div>
            <div class="diplomaName diplomaCenter diplomaMargins">{{$diplomaName}}</div>
            <div class="diplomaDod diplomaCenter diplomaMargins">har fullført kompetansepakken</div>
            <div class="diplomaCourseName diplomaCenter diplomaMargins">{{$diplomaCourseName}}</div>
            <div class="diplomaDescription diplomaCente diplomaMargins">{{$diplomaCourseDescription}}</div>
            <div class="diplomaIssuedPlace diplomaCenter diplomaMargins">Tromsø {{$diplomaDate}}</div> 
        </div>
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
