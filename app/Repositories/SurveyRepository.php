<?php

namespace App\Repositories;
use App\Exceptions\Survey\SurveyAlreadySubmittedException;
use App\Http\Requests\Survey\AddSurveyRequest;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use App\Models\SurveySubmissionData;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class SurveyRepository
{

    /**
     * Create a new survey and return the id
     *
     * @param AddSurveyRequest $surveyContent
     * @return int ID of the created survey
     */
    public function createSurvey(AddSurveyRequest $surveyContent): int
    {
        $survey = Survey::create([
            'course_id' => $surveyContent->course_id,
            'module_id' => $surveyContent->module_id,
            'title_form' => $surveyContent->title,
            'title_internal' => $surveyContent->title_internal,
            'created' => date_create()->format('Y-m-d H:i:s'),
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

    /**
     * Create a custom survey question with type 'likert_scale_5pt'.
     *
     * @param int $surveyId
     * @param array $questionContent Array containing associative arrays with the keys 'text' (required, string)
     * and 'machine_name' (optional, string)
     * @return void
     */
    public function createCustomSurveyQuestion(int $surveyId, array $questionContent): void
    {
        SurveyQuestion::create([
            'survey_id' => $surveyId,
            'machine_name' => $questionContent['machine_name'],
            'text' => $questionContent['text'],
            'question_type' => "likert_scale_5pt",
            'required' => true,
            'deleted' => false
        ]);
    }

    /**
     * Inserts the default likert scale questions into the database for survey
     *
     * @param int $surveyId
     * @return void
     */
    public function createDefaultScalaQuestions(int $surveyId): void

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
                'machine_name' => "standard_question_$questionNr",
                'text' => $questionTexts[$i],
                'question_type' => "likert_scale_5pt",
                'required' => true,
                'deleted' => false
            ]);
        }
    }

    /**
     * Inserts the default essay question into the database for survey.
     *
     * @param int $surveyId
     * @return void
     */
    public function createDefaultEssayQuestion(int $surveyId): void
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
     * Creates a submission for a given survey for a given user.
     *
     *
     * @param int $surveyId
     * @param int $userID
     * @param array $answers Array of associative arrays with keys 'question_id' (int) and 'value' (string)
     * @throws SurveyAlreadySubmittedException
     */
    public function createUserSubmission(int $surveyId, int $userID, array $answers): void {
        // Prevent duplicate submissions
        $existingSubmission = SurveySubmission::where([
            ['survey_id', '=', $surveyId],
            ['user_id', '=', $userID],
            ['deleted', '=', false]
        ])->first();
        if ($existingSubmission) {
            logger("User $userID tried to submit survey $surveyId twice");
            throw new SurveyAlreadySubmittedException();
        }

        $surveySubmission = SurveySubmission::create([
            'survey_id' => $surveyId,
            'user_id' => $userID,
            'submitted' => date_create()->format('Y-m-d H:i:s'),
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

    /**
     * Delete a submission for a user for a given survey.
     *
     * @param int $surveyID
     * @param int $userID
     * @return void
     */
    public function deleteUserSubmission(int $surveyID, int $userID): void
    {
        $surveySubmission = SurveySubmission::where([
            ['survey_id', '=', $surveyID],
            ['user_id', '=', $userID],
            ['deleted', '=', false]
        ])->first();
        if ($surveySubmission) {
            $surveySubmission->deleted = true;
            $surveySubmission->save();
        }
    }

    /**
     * Returns a survey with all it's questions.
     *
     * Does not include submissions.
     *
     * @param int $surveyId
     * @return mixed
     */
    public function getSurvey(int $surveyId): mixed
    {
        return Survey::with(['questions' => function ($query) {
            $query->where('deleted', false);
        }])->where([['deleted', false], ['id', $surveyId]])->first();
    }

    /**
     * @param int $courseId
     * @param int|null $groupId
     * @return mixed
     * @throws Exception
     */
    private function getSurveysWithOptionalFiltering(int $courseId, ?int $groupId): mixed
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


        return self::countScalaQuestionResponseValues($surveys);
    }

    /**
     * Returns all surveys, with submissions, for a given course.
     *
     * @param int $courseId
     * @return mixed
     * @throws Exception
     */
    public function getSurveys(int $courseId): mixed
    {
        return $this->getSurveysWithOptionalFiltering($courseId, null);
    }

    /**
     * Returns all surveys, with submissions, for a given course and group.
     *
     * @param int $courseId
     * @param int $groupId
     * @return mixed
     * @throws Exception
     */
    public function getSurveysFilteredOnGroup(int $courseId, int $groupId): mixed
    {
        return $this->getSurveysWithOptionalFiltering($courseId, $groupId);
    }

    /**
     * Returns a given survey with a given user's submission.
     *
     * @param int $surveyId
     * @param int $userId
     * @return mixed
     */
    public function getStudentSubmission(int $surveyId, int $userId): mixed
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
    private static function countScalaQuestionResponseValues($surveys)
    {
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


    //---------- METHODS TO EXCLUDE ESSAY QUESTIONS -----------//

    /**
     * @param int $courseId
     * @param int|null $groupId
     * @return mixed
     * @throws Exception
     */
    private function getSurveysWithOptionalFilteringNoEssays(int $courseId, ?int $groupId): mixed
    {
        $surveys = Survey::with([
            'questions' => function ($query) {
                $query->where('deleted', false)
                    ->where('question_type', '!=', 'essay');
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


        return self::countScalaQuestionResponseValues($surveys);
    }

    /**
     * Returns all surveys, with submissions excluding essay questions, for a given course and group.
     *
     * @param int $courseId
     * @param int $groupId
     * @return mixed
     * @throws Exception
     */
    public function getSurveysFilteredOnGroupExcludingEssays(int $courseId, int $groupId): mixed
    {
        return $this->getSurveysWithOptionalFilteringNoEssays($courseId, $groupId);
    }


}
