<?php

namespace App\Repositories;

use App\Models\Subtitles;
use Vimeo\Laravel\Facades\Vimeo;
use GuzzleHttp\Client;

class SubtitlesRepository
{
    public static function getOrCreateSubtitles(int $videoId)
    {
        $subtitles = self::getSutitlesFromDatabase($videoId);
        if ($subtitles->isNotEmpty()) 
        {
            return $subtitles;
        } 

        $href = "/videos/" . $videoId . "/texttracks";
        logger($href);
        $result = Vimeo::request($href, [], 'GET');
        $languagesAvailable = $result["body"]["data"];
        logger($languagesAvailable);
        foreach($languagesAvailable as $languageAvailable) {         
            $vttHref = $languageAvailable["link"];
            logger($vttHref);
            $client = new Client();
            $res = $client->request('GET', $vttHref, []);
            $vtt = $res->getBody();
            self::createSubtitles($videoId, $vtt, $languageAvailable["language"]);
        }
        return self::getSutitlesFromDatabase($videoId);
    }
    private static function getSutitlesFromDatabase(int $videoId)
    {
        $subtitles = Subtitles::where('videoId', $videoId)->get();
        if ($subtitles) 
        {
            logger("Found subtitles in KPAS database.");
            return $subtitles;
        } 
        return null;
    }
    private static function createSubtitles($videoId, $vtt, $language): Subtitles 
    {
        $subtitles = new Subtitles;

        $subtitles->videoId = $videoId;
        $subtitles->raw_subtitles = $vtt;
        $subtitles->language = $language;
        $subtitles->save();        
        return $subtitles;
    }
}
