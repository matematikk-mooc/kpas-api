<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessXmlResponse;
use Vimeo\Laravel\Facades\Vimeo;
use GuzzleHttp\Client;

//https://github.com/mantas-done/subtitles
use \Done\Subtitles\Subtitles;

class VimeoController extends Controller
{
    public function index(int $vimeoId): SuccessXmlResponse
    {
        $href = "/videos/" . $vimeoId . "/texttracks";
        logger($href);
        $result = Vimeo::request($href, [], 'GET');
        $vttHref = $result["body"]["data"][0]["link"];
        logger($vttHref);
        $client = new Client();
        $res = $client->request('GET', $vttHref, []);
        $vtt = $res->getBody();
        $subtitles = Subtitles::load($vtt, 'vtt');
        $subtitlesArray = $subtitles->getInternalFormat();
        $transcript = '<?xml version="1.0" encoding="utf-8" ?><transcript>';
        foreach ($subtitlesArray as $subtitle) {
            $start = $subtitle["start"];
            $end = $subtitle["end"];
            $lines = $subtitle["lines"];
            foreach ($lines as $line) {
                logger($line);
                $transcript .= '<text start="' . $start .'" end="'. $end .'">' . $line . "</text>";
            }
         }
         $transcript .= "</transcript>";
         //        return new SuccessResponse($result["body"]);
        return new SuccessXmlResponse($transcript);
    }
}
