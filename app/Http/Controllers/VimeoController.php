<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessXmlResponse;
use App\Http\Responses\ErrorResponse;
use App\Repositories\SubtitlesRepository;
//https://github.com/mantas-done/subtitles
use \Done\Subtitles\Subtitles;

class VimeoController extends Controller
{
    public function index(int $vimeoId)
    {
        $subtitles = SubtitlesRepository::getOrCreateSubtitles($vimeoId);
        if(!$subtitles) {
            return new ErrorResponse("No subtitles available.");            
        }
        $vtt = $subtitles->raw_subtitles;

        $subtitles = Subtitles::load($vtt, 'vtt');
        $subtitlesArray = $subtitles->getInternalFormat();
        $transcript = '<?xml version="1.0" encoding="utf-8" ?><transcript>';
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
         $transcript .= "</transcript>";
         //        return new SuccessResponse($result["body"]);
        return new SuccessXmlResponse($transcript);
    }
}
