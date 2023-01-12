<?php

namespace App\Repositories;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use App\Models\SurveySubmissionData;
use Illuminate\Database\Eloquent\Model;


class SurveyRepository
{
    public function createSurvey(AddSurveyRequest $surveyContent)
    {
        $survey = Survey::create([
            'course_id' => $surveyContent->course_id,
            'title_form' => $surveyContent->title,
            'title_internal' => $surveyContent->title_internal,
            'created' => date_create('now')->format('Y-m-d H:i:s'),
            'deleted' => false
        ]);
        $survey = $survey->refresh();

        $surveyId = $survey->id;

        $this->createDefaultQuestions($surveyId, $surveyContent->required_default);

        $customQuestions = $surveyContent->questions;
        for ($i = 0; $i < count($customQuestions); $i++){
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

    public function createDefaultQuestions (int $surveyId, bool $required)
    {
        $questionTexts = array(
            "I hvilken grad har du lært noe gjennom å ha arbeidet med innholdet i denne modulen?",
            "I hvilken grad er innholdet i modulen praksisrelevant?",
            "I hvilken grad tror du arbeidet med innholdet i denne modulen vil føre til praksisendring?"
        );

        for($i = 0; $i < count($questionTexts); $i++){
            $questionNr = $i+1;
            SurveyQuestion::create([
                'survey_id' => $surveyId,
                'machine_name' => "standard_question_{$questionNr}",
                'text' => $questionTexts[$i],
                'question_type' => "likert_scale_5pt",
                'required' => $required,
                'deleted' => false
            ]);
        }

        SurveyQuestion::create([
            'survey_id' => $surveyId,
            'machine_name' => "standard_question_essay",
            'text' => "Har du forslag til forbedringer eller andre kommentarer angående denne modulen?",
            'question_type' => "essay",
            'required' => $required,
            'deleted' => false
        ]);
    }

    /**
     * Gets a survey by id. If no survey is found, null is returned.
     *
     * @param int $id
     * @return Survey|null
     */
    public function getSurvey(int $id): Survey|null
    {
        return Survey::where(['id' => $id, 'deleted' => false])->first();
    }

    /**
     * Gets the questions for a survey. If no questions are found, an empty array is returned.
     * @param int $surveyID
     * @return array
     */
    public function getSurveyQuestions(int $surveyID): array
    {
        return SurveyQuestion::where(['survey_id' => $surveyID, 'deleted' => false])->get()->toArray();
    }

    /**
     * Gets a submission for a survey by survey id and user id.
     *
     * If the user hasn't submitted the survey, null is returned.
     *
     * @param int $surveyID
     * @param int $userID
     * @return SurveySubmission|null
     */
    public function getSurveySubmission(int $surveyID, int $userID): SurveySubmission|null
    {
        return SurveySubmission::where(['survey_id' => $surveyID, 'user_id' => $userID, 'deleted' => false])->first();
    }

    /**
     * Gets the data for a survey submission by submission id.
     *
     * If no data is found, an empty array is returned.
     *
     * @param int $submissionID
     * @return array
     */
    public function getSurveySubmissionData(int $submissionID): array
    {
        return SurveySubmissionData::where(['submission_id' => $submissionID])->get()->toArray();
    }
}
