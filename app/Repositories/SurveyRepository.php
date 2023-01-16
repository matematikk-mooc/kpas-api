<?php

namespace App\Repositories;
use App\Exceptions\Survey\SurveyAlreadySubmittedException;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use App\Models\SurveySubmissionData;
use App\Models\JoinCanvasGroupUser;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


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
        $this->createDefaultScalaQuestions($surveyId);
        $customQuestions = $surveyContent->questions;
        for ($i = 0; $i < count($customQuestions); $i++) {
            if ($customQuestions[$i]['text'] != '') {
                $this->createCustomSurveyQuestion($surveyId, $customQuestions[$i]);
            }
        }

        $this->createDefaultEssayQuestion($surveyId);

        return $surveyId;
    }

    public function createCustomSurveyQuestion(int $surveyId, array $questionContent)
    {
        $surveyQuestion = SurveyQuestion::create([
            'survey_id' => $surveyId,
            'machine_name' => $questionContent['machine_name'],
            'text' => $questionContent['text'],
            'question_type' => "likert_scale_5pt",
            'required' => true,
            'deleted' => false
        ]);
    }

    public function createDefaultScalaQuestions(int $surveyId)

    {
        $questionTexts = array(
            "I hvilken grad har du lært noe gjennom å ha arbeidet med innholdet i denne modulen?",
            "I hvilken grad er innholdet i modulen praksisrelevant?",
            "I hvilken grad tror du arbeidet med innholdet i denne modulen vil føre til praksisendring?"
        );

        for ($i = 0; $i < count($questionTexts); $i++) {
            $questionNr = $i + 1;
            SurveyQuestion::create([
                'survey_id' => $surveyId,
                'machine_name' => "standard_question_{$questionNr}",
                'text' => $questionTexts[$i],
                'question_type' => "likert_scale_5pt",
                'required' => true,
                'deleted' => false
            ]);
        }
    }

    public function createDefaultEssayQuestion(int $surveyId)
    {

        SurveyQuestion::create([
            'survey_id' => $surveyId,
            'machine_name' => "standard_question_essay",
            'text' => "Har du forslag til forbedringer eller andre kommentarer angående denne modulen?",
            'question_type' => "essay",
            'required' => false,
            'deleted' => false
        ]);
    }

    /**
     * @throws Exception
     */
    public function createUserSubmission($answers, int $surveyId, int $userID): void {
        // Prevent duplicate submissions
        $existingSubmission = SurveySubmission::where([
            ['survey_id', '=', $surveyId],
            ['user_id', '=', $userID],
            ['deleted', '=', false]
        ])->first();
        if ($existingSubmission) {
            logger("User {$userID} tried to submit survey {$surveyId} twice");
            throw new SurveyAlreadySubmittedException();
        }

        $surveySubmission = SurveySubmission::create([
            'survey_id' => $surveyId,
            'user_id' => $userID,
            'submitted' => date_create('now')->format('Y-m-d H:i:s'),
            'deleted' => false
        ]);

        foreach ($answers as $answer) {
            SurveySubmissionData::create([
                'submission_id' => $surveySubmission->id,
                'question_id' => $answer['question_id'],
                'value' => $answer['value']
            ]);
        }
    }

    public function deleteUserSubmission(int $surveyId, int $userID): void {
        $surveySubmission = SurveySubmission::where([
            ['survey_id', '=', $surveyId],
            ['user_id', '=', $userID],
            ['deleted', '=', false]
        ])->first();
        if ($surveySubmission) {
            $surveySubmission->deleted = true;
            $surveySubmission->save();
        }
    }

    public function getSurvey(int $surveyId)
    {
        return Survey::with(['questions' => function ($query) {
            $query->where('deleted', false);
        }])->where([['deleted', false], ['id', $surveyId]])->first();
    }

    /**
     * @throws Exception
     */
    private function getSurveysWithOptionalFiltering(int $courseId, ?int $groupId)
    {
        $surveys = Survey::with([
            'questions' => function ($query) {
                $query->where('deleted', false);
            },
            'questions.submissionData' => function ($query) use ($groupId) {
                $query->whereHas('submission', function ($query) use ($groupId) {
                    if ($groupId) {
                        $query->whereIn('user_id', function ($query) use ($groupId) {
                            $query->select('canvas_user_id')->from('join_canvas_group_users')->where('canvas_group_id', $groupId);
                        })->where('deleted', false);
                    }
                });
            },
            'questions.submissionData.submission:id,submitted'
        ])->where([['course_id', '=', $courseId], ['deleted', '=', false]])->get();


        $surveys = self::countScalaQuestionResponseValues($surveys);

        return $surveys;
    }

    /**
     * @throws Exception
     */
    public function getSurveys(int $courseId)
    {
        return $this->getSurveysWithOptionalFiltering($courseId, null);
    }

    /**
     * @throws Exception
     */
    public function getSurveysFilteredOnGroup(int $courseId, int $groupId)
    {
        return $this->getSurveysWithOptionalFiltering($courseId, $groupId);
    }

    public function getStudentSubmission(int $surveyId, int $userId)
    {
        return SurveySubmission::with([
            'survey',
            'survey.questions' => function ($query) {
                $query->where('deleted', false);
            },
            'survey.questions.submissionData' => function ($query) use ($userId) {
                $query->whereHas('submission', function ($query) use ($userId) {
                    $query->where([['user_id', '=', $userId], ['deleted', '=', false]]);
                });
            }
        ])->where([['survey_id', '=', $surveyId], ['user_id', '=', $userId], ['deleted', '=', false]])->first();
    }

    /**
     * @throws Exception
     */
    private static function countScalaQuestionResponseValues($surveys) {
        $surveys = json_decode($surveys);
        foreach($surveys as $survey){
            foreach($survey->questions as $question){
                if ($question->question_type == "likert_scale_5pt"){
                    $question->submission_data = self::getLikertScale5ptSums(array_column($question->submission_data, 'value'));
                }
            }
        }
        return $surveys;
    }

    /**
     * @param string[] $values String-array where one string is value of one submission
     * @return array Array with keys "likert_scale_5pt_none", "likert_scale_5pt_1", "likert_scale_5pt_2", "likert_scale_5pt_3", "likert_scale_5pt_4", "likert_scale_5pt_5" etc., and values are their counts
     * @throws Exception
     */
    private static function getLikertScale5ptSums(array $values): array
    {
        $options = SurveyQuestion::getLikertScaleOptions();

        $sums = [];
        foreach ($options as $key => $value) {
            $sums[$key] = 0;
        }

        foreach ($values as $value) {
            if (!array_key_exists($value, $sums)) {
                throw new Exception("Invalid likert scale 5pt value: $value");
            }
            $sums[$value] += 1;
        }
        return $sums;
    }

}
