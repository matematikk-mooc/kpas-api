<?php

namespace App\Repositories;

use App\Models\Subtitles;
use Vimeo\Laravel\Facades\Vimeo;
use GuzzleHttp\Client;

class SubtitlesRepository
{
    public static function getOrCreateSubtitles(int $videoId): ?Subtitles
    {
        $subtitles = Subtitles::where('videoId', $videoId)->first();
        if ($subtitles) 
        {
            logger("Found subtitles in KPAS database.");
            return $subtitles;
        } 
        $href = "/videos/" . $videoId . "/texttracks";
        logger($href);
        $result = Vimeo::request($href, [], 'GET');
        logger(print_r($result,true));
        $noOfSubtitleTracks = $result["body"]["total"];
        if($noOfSubtitleTracks > 0) {
            $vttHref = $result["body"]["data"][0]["link"];
            logger($vttHref);
            $client = new Client();
            $res = $client->request('GET', $vttHref, []);
            $vtt = $res->getBody();
            return self::createSubtitles($videoId, $vtt);
        }
        return null;
    }
    private static function createSubtitles($videoId, $vtt): Subtitles 
    {
        $subtitles = new Subtitles;

        $subtitles->videoId = $videoId;
        $subtitles->raw_subtitles = $vtt;

        $subtitles->save();        
        return $subtitles;
    }
}
