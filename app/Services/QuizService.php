<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
class QuizService
{
    protected $guzzleClient;

    public function getQuizHtml() {
        logger("getQuizData");

        $this->guzzleClient = new Client();
        $quizData = $this->request("502", "2720");
        
        return view('main.quiz')->withQuizData($quizData);
    }
    
    protected function request(string $courseId, string $quizId, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "https://172.17.0.1:8080/api/statistics/course/{$courseId}/quizzes";
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
        $url = "https://statistics-api-staging.azurewebsites.net/api/statistics/course/{$courseId}/quizzes?format=json";
      
        $this->guzzleClient = new Client();

        return $this->guzzleClient->request('GET', $url, [], [], false);
    }

    public function getCourseQuizIdStatistics(int $courseId, int $quizId) {
        $url = "https://172.17.0.1:8080/api/statistics/course/{$courseId}/quiz/{$quizId}?format=json";

        $this->guzzleClient = new Client();

        return $this->guzzleClient->request($url);
    }

}