<?php
namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\SuccessXmlResponse;
use App\Http\Responses\ErrorXmlResponse;
use App\Repositories\SubtitlesRepository;
//https://github.com/mantas-done/subtitles
use \Done\Subtitles\Subtitles;

class VimeoController extends Controller
{
    public function index(int $vimeoId)
    {
        logger("VimeoController::index vimeo_id=" . $vimeoId);
        $subtitles = SubtitlesRepository::getOrCreateSubtitles($vimeoId);
        if(!$subtitles->first()["language"]) return new ErrorXmlResponse("Videotranskript er dessverre ikke tilgjengelig for denne videoen.");

        $transcript = '<?xml version="1.0" encoding="utf-8" ?><transcript>';
        foreach($subtitles as $subtitle) {
            $subtitleLanguage = $subtitle["language"];
            logger("VimeoController::index vimeo_id=" . $vimeoId . " subtitle_language=" . $subtitleLanguage);
            $vtt_subtitles = Subtitles::loadFromString($subtitle["raw_subtitles"], 'vtt');
            $vtt_subtitlesArray = $vtt_subtitles->getInternalFormat();
            $transcript .= '<language lang="' . $subtitleLanguage . '">';
            foreach ($vtt_subtitlesArray as $vtt_subtitle) {
                $start = $vtt_subtitle["start"];
                $dur = $vtt_subtitle["end"] - $vtt_subtitle["start"];
                $lines = $vtt_subtitle["lines"];
                $xmlLines = "";
                foreach ($lines as $line) {
                    $xmlLines .= $line . " ";
                }
                $xmlLinesStripped = strip_tags($xmlLines);
                $xmlLinesStrippedFromControlCharacters = preg_replace('/[\x00-\x1F\x7F]/', '', $xmlLinesStripped);
                $transcript .= '<text start="' . $start .'" dur="'. $dur .'"><![CDATA[' . $xmlLinesStrippedFromControlCharacters . "]]></text>";
            }
            $transcript .= "</language>";
        }
        $transcript .= "</transcript>";
        return new SuccessXmlResponse($transcript);
    }

    public function reset(int $vimeoId)
    {
        logger("VimeoController::reset vimeo_id=" . $vimeoId);
        $result = SubtitlesRepository::deleteSubtitles($vimeoId);
        if($result) {
            return new SuccessResponse('Success');
        } 
        return new ErrorResponse("Could not delete subtitles with supplied id. Error:{$result}");
    }
}
