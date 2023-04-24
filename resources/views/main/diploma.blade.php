@extends('layouts.app')


@section('content')
@if($hasDeservedDiploma)
    <div class="mainDiplomaPage diplomaBorder">
        <div>
            <div class="diplomaName diplomaMargins">{{$diplomaName}}</div>
            <div class="diplomaDod diplomaMargins">har fullført kompetansepakken</div>
            <div class="diplomaCourseName diplomaMargins">{{$diplomaCourseName}}</div>
            <div class="diplomaDescription">{!!$diplomaCourseDescription!!}</div>
            <div class="diplomaIssuedBy diplomaCenter">{!!$diplomaDeliveredBy!!}</div>
            <div class="diplomaIssuedPlace">Tromsø {{$diplomaDate}}</div>
        </div>
        <div class="diplomaLogos">
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
