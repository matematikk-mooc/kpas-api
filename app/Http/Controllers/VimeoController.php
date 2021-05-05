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
        if($subtitles->isEmpty()) {
            return new ErrorResponse("No subtitles available.");            
        }
        logger(print_r($subtitles, true));
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
