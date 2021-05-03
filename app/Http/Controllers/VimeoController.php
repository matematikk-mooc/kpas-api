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
        $languagesAvailable = $result["body"]["data"];
        logger($languagesAvailable);
        $transcript = '<?xml version="1.0" encoding="utf-8" ?><transcript>';
        foreach($languagesAvailable as $languageAvailable) {         
            $transcript .= '<language lang="' . $languageAvailable["language"] . '">';
            $vttHref = $languageAvailable["link"];
            logger($vttHref);
            $client = new Client();
            $res = $client->request('GET', $vttHref, []);
            $vtt = $res->getBody();
            $subtitles = Subtitles::load($vtt, 'vtt');
            $subtitlesArray = $subtitles->getInternalFormat();
            foreach ($subtitlesArray as $subtitle) {
                $start = $subtitle["start"];
                $dur = $subtitle["end"] - $subtitle["start"];
                $lines = $subtitle["lines"];
                $xmlLines = "";
                foreach ($lines as $line) {
                    logger($line);
                    $xmlLines .= $line . " ";
                }
                $transcript .= '<text start="' . $start .'" dur="'. $dur .'">' . $xmlLines . "</text>";
            }
            $transcript .= "</language>";
        }
        $transcript .= "</transcript>";
        return new SuccessXmlResponse($transcript);
    }
}
