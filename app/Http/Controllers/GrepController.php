<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use EasyRdf;


class GrepController extends Controller
{
    private function getGF5() 
    {
        $sparql = new EasyRdf\Sparql\Client('http://sandkasse-data.udir.no:7200/repositories/KL06_201906_2102');
        $query = '
        PREFIX u: <http://psi.udir.no/ontologi/kl06/>
        PREFIX d: <http://psi.udir.no/kl06/>
        select ?lpJson ?kmJson ?kmTittel ?lpKode ?lpTittel where {
            ?km a u:kompetansemaal_lk20 ;
            u:tittel ?kmTittel ;
            u:url-data ?kmJson ;
            u:tilhoerer-laereplan ?lp ;
            u:tilknyttede-grunnleggende-ferdigheter ?grf .
            FILTER (lang(?kmTittel) = "default")
            FILTER (REGEX(str(?grf), "GF5", "i"))
            ?lp u:url-data ?lpJson ;
            u:tittel ?lpTittel ;
            u:kode ?lpKode .
            FILTER (lang(?lpTittel) = "default") 
        } ORDER BY ?lp ?km
        ';
        $result = $sparql->query($query);
        return $result;
    }
    public function gf5()
    {
        $result = $this->getGF5();
        $data = ['grep' => $result];
        logger($data);
        return view('grep.grep', $data);
    }    
}

