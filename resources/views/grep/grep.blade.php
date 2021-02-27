<html>
    <head>
            <meta http-equiv="refresh" content="600"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <h1>Digitale ferdigheter i fag</h1>
    @foreach ($grep as $g)
        @if($loop->first OR $g->lpKode != $grep[$loop->index-1]->lpKode)
            @if(!$loop->first)
                </ul>
            @endif
            <h2><a target="_blank" href="https://www.udir.no/lk20/{{$g->lpKode}}">{{$g->lpTittel}}</a></h2>
            <ul>
        @endif
        <li>
        {{$g->kmTittel}}
        </li>
    @endforeach
    </ul>    
    </body>
</html>

