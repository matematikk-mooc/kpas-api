<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class QuizService
{
    protected $guzzleClient;
    protected $statistics_base_url;

    public function __construct()
    {
        $this->statistics_base_url = config('statistics-api.base_url');
    }

    public function getQuizHtml($settings) {
        logger("getQuizData");

        return view('main.quiz')->withSettings($settings);
    }

    protected function request(string $courseId, string $quizId, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {   
        $fullUrl = "{$this->statistics_base_url}/statistics/course/{$courseId}/quizzes";
        logger($fullUrl);

        try {
            $content = [];
            $response = $this->guzzleClient->request($method, $fullUrl, [
                'form_params' => $data,
                'headers' => $headers,
                'verify' => false,
            ]);

            $decodedContent = json_decode($response->getBody()->getContents());
            $content = is_array($decodedContent) ? array_merge($content, $decodedContent) : $decodedContent;

            logger("QuizService: returning content");

            return $content;
        } catch (ClientException $exception) {
            logger("StatisticsService.request exception:");
            throw $exception;
        }
    }

    public function getCourseQuizzesStatistics(int $courseId) {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/quizzes?format=json";

        $this->guzzleClient = new Client();

        return $this->guzzleClient->request('GET', $url, [], [], false);
    }

    public function getCourseQuizIdStatistics(int $courseId, int $quizId) {
        $url = "{$this->statistics_base_url}/statistics/course/{$courseId}/quiz/{$quizId}?format=json";

        $this->guzzleClient = new Client();

        return $this->guzzleClient->request($url);
    }

}