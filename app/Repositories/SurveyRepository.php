<?php

namespace App\Repositories;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Model;


class SurveyRepository
{
    public function createSurvey(AddSurveyRequest $surveyContent) 
    {
        $survey = Survey::create([
            'course_id' => $surveyContent->courseid,
            'title_form' => $surveyContent->title,
            'title_internal' => $surveyContent->title_internal,
            'created' => date_create('now')->format('Y-m-d H:i:s'),
            'deleted' => false
        ]);
        $survey = $survey->refresh(); 

        $surveyId = $survey->id;

        $customQuestions = $surveyContent->questions;
        for ($i = 0; $i < count($customQuestions); $i++){
            logger($customQuestions[$i]['text']);
            if($customQuestions[$i]['text'] != ''){
                $this->createCustomSurveyQuestion($surveyId, $customQuestions[$i]);
            }
        }


        return $surveyId;
    }


    public function createCustomSurveyQuestion (int $surveyId, array $questionContent)
    {
        $surveyQuestion = SurveyQuestion::create([
            'survey_id' => $surveyId,
            'machine_name' => $questionContent['machine_name'],
            'text' => $questionContent['text'], 
            'question_type' => "likert_scale_5pt",
            'required' => $questionContent['required'], 
            'deleted' => false
        ]);
    }
}
