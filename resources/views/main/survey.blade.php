@php
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Repositories\SurveyRepository;
@endphp
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
            surveyConfigurationError();
            return;
        }

        if (intval($survey->course_id) !== $course_id) {
            surveyConfigurationError();
            return;
        }

        if (count($survey->questions) == 0) {
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
