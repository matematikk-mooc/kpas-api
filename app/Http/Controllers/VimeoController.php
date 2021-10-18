<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessXmlResponse;
use App\Http\Responses\ErrorXmlResponse;
use App\Repositories\SubtitlesRepository;
//https://github.com/mantas-done/subtitles
use \Done\Subtitles\Subtitles;

class VimeoController extends Controller
{
    public function index(int $vimeoId)
    {
        $subtitles = SubtitlesRepository::getOrCreateSubtitles($vimeoId);

        if(!$subtitles->first()["language"]) {
            return new ErrorXmlResponse("Videotranskript er dessverre ikke tilgjengelig for denne videoen.");            
        }
        $transcript = '<?xml version="1.0" encoding="utf-8" ?><transcript>';
        foreach($subtitles as $subtitle) {         
            $vtt_subtitles = Subtitles::load($subtitle["raw_subtitles"], 'vtt');
            $vtt_subtitlesArray = $vtt_subtitles->getInternalFormat();
            $transcript .= '<language lang="' . $subtitle["language"] . '">';
            foreach ($vtt_subtitlesArray as $vtt_subtitle) {
                $start = $vtt_subtitle["start"];
                $dur = $vtt_subtitle["end"] - $vtt_subtitle["start"];
                $lines = $vtt_subtitle["lines"];
                $xmlLines = "";
                foreach ($lines as $line) {
                    $xmlLines .= $line . " ";
                }
                $xmlLinesStripped = strip_tags($xmlLines);
                $transcript .= '<text start="' . $start .'" dur="'. $dur .'"><![CDATA[' . $xmlLinesStripped . "]]></text>";
            }
            $transcript .= "</language>";
        }
        $transcript .= "</transcript>";
        return new SuccessXmlResponse($transcript);
    }
}
