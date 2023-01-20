@php use App\Models\Survey;use App\Models\SurveyQuestion;use App\Repositories\SurveyRepository;use Bugsnag\BugsnagLaravel\Facades\Bugsnag; @endphp
@extends('layouts.app')

@section('content')
    @php

        function surveyConfigurationError() {
            echo '<div style="font-size: 20px; font-weight: bold; color: red;" role="alert">';
            echo "Spørreundersøkelsen er satt opp feil. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.";
            echo '</div>';
        }
        $survey_id = intval($_REQUEST["survey_id"]);
        $user_id = intval($settings["custom_canvas_user_id"]);
        $course_id = intval($settings["custom_canvas_course_id"]);

        $surveyRepository = new SurveyRepository();
        $survey = $surveyRepository->getSurvey($survey_id);


        if ($survey == null) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ', loaded from course with ID ' . $course_id . ' is not found in database.');
           surveyConfigurationError();
            return;
        }
        if (intval($survey->course_id) !== $course_id) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ' is loaded from course with ID ' .
                                    $course_id . ', but belongs to course with ID ' . $survey->course_id);
           surveyConfigurationError();
            return;
        }
        if (count($survey->questions) == 0) {
            Bugsnag::notifyError('Survey', 'Survey with ID ' . $survey_id . ' has no questions.');
            surveyConfigurationError();
            return;
        }

        $likertScale5ptOptions = SurveyQuestion::getLikertScaleOptions();

        $existingSubmission = $surveyRepository->getStudentSubmission($survey_id, $user_id);

    @endphp

    <survey-view :survey="{{ json_encode($survey) }}"
                 :questions="{{ json_encode($survey->questions) }}"
                 :likert5ops="{{ json_encode($likertScale5ptOptions) }}"
                 :existing_submission="{{ json_encode($existingSubmission) }}">
    </survey-view>

@endsection

@section('scripts')
    <script>window.cookie = '{{ session()->getId() }}';</script>
@endsection
