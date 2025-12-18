<?php

namespace App\Repositories;

use App\Models\Subtitles;
use Vimeo\Laravel\Facades\Vimeo;
use GuzzleHttp\Client;

class SubtitlesRepository
{
    public static function getOrCreateSubtitles(int $videoId)
    {
        $subtitles = self::getSubtitlesFromDatabase($videoId);
        if ($subtitles->isNotEmpty()) return $subtitles;

        $href = "/videos/" . $videoId . "/texttracks";
        $result = Vimeo::request($href, [], 'GET');

        if(!isset($result["body"]) || !isset($result["body"]["data"]) || !count($result["body"]["data"])) {
            self::createNoSubtitles($videoId);
        } else {
            $languagesAvailable = $result["body"]["data"];
            foreach($languagesAvailable as $languageAvailable) {         
                $vttHref = $languageAvailable["link"];
                $client = new Client();
                $res = $client->request('GET', $vttHref, []);
                $vtt = $res->getBody();
                self::createSubtitles($videoId, $vtt, $languageAvailable["language"]);
            }
        }
        return self::getSubtitlesFromDatabase($videoId);
    }
    public static function deleteSubtitles(int $videoId)
    {
        logger("SubtitlesRepository::deleteSubtitles video_id={$videoId}");
        return Subtitles::where('videoId', $videoId)->delete();
    }
    private static function getSubtitlesFromDatabase(int $videoId)
    {
        return Subtitles::where('videoId', $videoId)->get();
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
    private static function createNoSubtitles($videoId): Subtitles 
    {
        $subtitles = new Subtitles;

        $subtitles->videoId = $videoId;
        $subtitles->save();
        return $subtitles;
    }
}
