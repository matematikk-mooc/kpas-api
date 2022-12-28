<?php

namespace App\Repositories;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Model;


class SurveyRepository
{
    public function createSurvey(AddSurveyRequest $surveyContent) {
        $survey = Survey::create([
            'course_id' => $surveyContent->courseid,
            'title_form' => $surveyContent->title,
            'title_internal' => $surveyContent->title_internal,
            'created' => date_create('now')->format('Y-m-d H:i:s'),
            'deleted' => false
        ]);
        $survey = $survey->refresh(); 
        return $survey->id;
    }


}