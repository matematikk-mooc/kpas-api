@php use App\Models\Survey;use App\Models\SurveyQuestion;use App\Repositories\SurveyRepository;use Bugsnag\BugsnagLaravel\Facades\Bugsnag; @endphp
@extends('layouts.app')

@section('content')
    @php

        function displayError($error) {
            echo '<div style="font-size: 20px; font-weight: bold; color: red;" role="alert">';
            echo $error;
            echo '</div>';
        }
        $survey_id = intval($_REQUEST["survey_id"]);
        $user_id = intval($settings["custom_canvas_user_id"]);
        $course_id = intval($settings["custom_canvas_course_id"]);

        $surveyRepository = new SurveyRepository();
        $survey = $surveyRepository->getSurvey($survey_id);

        if ($survey == null) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ', loaded from course with ID ' . $course_id . ' is not found in database.');
           displayError("Spørreundersøkelsen er satt opp feil. Ta kontakt med administrator.");
            return;
        }
        if (intval($survey->course_id) !== $course_id) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ' is loaded from course with ID ' .
                                    $course_id . ', but belongs to course with ID ' . $survey->course_id);
           displayError("Spørreundersøkelsen er satt opp feil. Ta kontakt med administrator.");
            return;
        }

        $questions = $surveyRepository->getSurveyQuestions($survey_id);
        if (count($questions) === 0) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ' has no questions.');
            displayError("Spørreundersøkelsen er satt opp feil. Ta kontakt med administrator.");
            return;
        }

        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();

        $existingSubmission = $surveyRepository->getSurveySubmission($survey_id, $user_id);
        $existingSubmissionAnswers = null;
        if ($existingSubmission != null) {
            $existingSubmissionAnswers = $surveyRepository->getSurveySubmissionData($existingSubmission->id);
        }

    @endphp

    <survey-view :survey="{{ json_encode($survey) }}"
                 :questions="{{ json_encode($questions) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}">
    </survey-view>

@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
