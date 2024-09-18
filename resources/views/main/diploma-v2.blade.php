@extends('layouts.app')

@section('content')
<div class="diploma-v2">
    @if(!$isCourseCompleted)
        <div class="diploma-v2-header">
            <h2>Diplomstatus: Ikke fullført</h2>
            <p>Det ser ut til at du ikke har oppfylt alle nødvendige krav enda. Nedenfor finner du en detaljert liste som viser hvilke krav som er oppfylt, hvilke som ikke er møtt, og hvilke som er valgfrie. For å anse et krav som fullført, kan det være nødvendig at du enten har besøkt en bestemt side eller klikket på "Merk som ferdig" knappen helt nederst på siden etter å ha lest gjennom relevant informasjon. Når alle krav er fullført, vil du bli tildelt et diplom som du kan laste ned på denne siden.</p>
        </div>
    @endif

    @if(!$isCourseCompleted)
        <ul>
            @foreach ($modules as $moduleItem)
            <li>
                <h3>{{ $moduleItem->title }}</h2>
            
                <ul>
                    @foreach ($moduleItem->pages as $pageItem)
                    <li class="item-type-{{ $pageItem->type }} {{ $pageItem->completionType != null ? 'has-requirement' : 'no-requirement' }} {{ $pageItem->completed ? 'is-completed' : '' }}">
                        <div class="item-checkmark ">
                            <h4>{{ $pageItem->title }}</h3>
                        
                            <span>{{ $pageItem->completionType != null ? ($pageItem->completed ? '✔' : 'X') : '–' }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    @else
        <div class="mainDiplomaPage diplomaBorder">
            <div>
                <div class="diplomaName diplomaMargins">{{$userName}}</div>
                <div class="diplomaDod diplomaMargins">har fullført kompetansepakken</div>
                <div class="diplomaCourseName diplomaMargins">{{$courseName}}</div>
                <div class="diplomaDescription">{!!$courseDiplomaDescription!!}</div>
                <div class="diplomaIssuedBy diplomaCenter">{!!$courseDiplomaBy!!}</div>
                <div class="diplomaIssuedPlace">Tromsø {{$courseDiplomaDate}}</div>
            </div>
            <div class="diplomaLogos">
                    @foreach ($courseDiplomaLogos as $logo)
                        <img class="diplomaIssuedByImage" alt="" src="images/{{$logo}}" title="{{$logo}}">
                    @endforeach
            </div>
        </div>
        <diploma-view>
        </diploma-view>
    @endif
</div>
@endsection

@section('scripts')

@if($isCourseCompleted)
<script>window.cookie = '{{ session()->getId() }}';</script>
@endif

@endsection
