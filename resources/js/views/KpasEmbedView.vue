@extends('layouts.app')
<template>
  <div class="kpas-embed-tools" v-if="toolToShow == null">
    <h4 class="kpas-embed-title">Hvilket KPAS verkøty ønsker du å bygge inn på siden?</h4>

    <div class="embed-tools">
      <a class="embed-tool" :href="urlRoleMode">
        <h5 class="kpas-embed-subtitle">Rolle- og gruppetilhørighet</h5>
        <p>Dette verktøyet lar brukere velge rolle og gruppetilhørighet. Innholdet tilpasses basert på hvilken rolle brukeren har valgt.</p>
      </a>

      <button class="embed-tool" @click="toolToShow = 'survey'">
        <h5 class="kpas-embed-subtitle">Undersøkelse</h5>
        <p>Dette verktøyet legger til en spørreundersøkelse med faste spørsmål samt mulighet for å legge inn egendefinerte spørsmål.</p>
      </button>

      <button class="embed-tool" @click="toolToShow = 'diplom'">
        <h5 class="kpas-embed-subtitle">Diplom</h5>
        <p>Dette verktøyet legger til et kompetansebevis som kan lastes ned av brukeren når alle sider med krav er oppfylt.</p>
      </button>

      <a class="embed-tool" :href="urlDashboardMode">
        <h5 class="kpas-embed-subtitle">Dashboard</h5>
        <p>Dette verktøyet gir en oversikt over svarene som er levert i "Undersøkelse"-verktøyet.</p>
      </a>

      <a class="embed-tool" :href="urlAdminDashboardMode">
        <h5 class="kpas-embed-subtitle">Forvaltning</h5>
        <p>Dette verktøyet presenterer innholdsprodusenter med to menyer. Meny én, "Helsesjekk", inneholder "Sanitetsjekk", "UU-sjekk", "Lenkesjekk" og "Videotekstsjekk". Meny to gir en statistikkoversikt for hele emnet, med mulighet for filtrering.</p>
      </a>

      <span class="embed-tool --hidden" />
    </div>
  </div>

  <div class="kpas-embed-button-wrapper" v-if="toolToShow != null">
    <button @click="toolToShow = null">Tilbake</button>
  </div>

  <div class="kpas-embed-survey" v-if="toolToShow == 'survey'">
    <h4 class="kpas-embed-title">Undersøkelse</h4>
    <p v-if="!this.hasModules">Fant ingen moduler, vennligst opprett en modul før opprettelse av undersøkelse.</p>

    <div v-if="this.hasModules">
      <div class="embed-survey-module">
        <label for="embed-survey-selector">Hvilken modul tilhører undersøkelsen?</label>
        <v-select
          id="embed-survey-selector"
          class="selector"
          label="name"
          placeholder="Velg modul"
          :options="coursemodules"
          @update:modelValue="updateSelectedModule"
        ></v-select>
      </div>

      <div v-if="this.surveyForm.isLoading">
        <LoadingIndicator />
      </div>

      <div v-if="this.isModuleSelected && !this.surveyForm.isLoading">
        <div class="embed-survey-find" v-if="this.hasSurveys">
          <h5 class="kpas-embed-subtitle">Velg eksiterenede undersøkelse</h5>

          <label for="embed-survey-find">Velg undersøkelse</label>
          <v-select
            id="embed-survey-find"
            class="selector"
            label="title_internal"
            placeholder="Velg undersøkelse"
            :options="surveys"
            v-model="surveySelected"
          ></v-select>
        </div>

        <div>
          <h5 class="kpas-embed-subtitle">{{ isSurveySelected ? `Undersøkelse #${surveySelected?.id}` : "Opprett ny undersøkelse" }} </h5>

          <div>
            <div class="embed-survey-title-wrapper">
              <div class="embed-survey-input-wrapper">
                <label for="embed-survey-title">Tittel for undersøkelse</label>

                <input
                  id="embed-survey-title"
                  type="text"
                  class="surveyForm"
                  maxlength="255"
                  name="title"
                  placeholder="Tittel"
                  v-model="surveyForm.title"
                  :value="isSurveySelected ? surveySelected?.title_internal : surveyForm.title"
                  :disabled="isSurveySelected"
                />
              </div>

              <div class="embed-survey-input-wrapper">
                <label>
                  <input
                    type="checkbox"
                    class="surveyForm"
                    name="use_custom_title"
                    v-model="surveyForm.useCustomTitle"
                    :value="isSurveySelected ? surveySelected?.title_form != null : surveyForm.useCustomTitle"
                    :disabled="isSurveySelected"
                  />
                  Legg til en annen tittel for sluttbrukere
                </label>

                <input
                  class="surveyForm"
                  type="text"
                  maxlength="255"
                  name="custom_title"
                  placeholder="Tittel for sluttbrukere"
                  v-if="!isSurveySelected && surveyForm.useCustomTitle || isSurveySelected && surveySelected?.title_form != null"
                  v-model="surveyForm.customTitle"
                  :value="isSurveySelected ? surveySelected?.title_form : surveyForm.customTitle"
                  :disabled="isSurveySelected"
                  />
              </div>
            </div>

            <div v-if="!isSurveySelected && surveyForm.title != '' || isSurveySelected && surveySelected?.title_internal != null">
              <p><b>Spørsmål for undersøkelsen</b></p>
              <p>Vi har standardspørsmål som legges til alle undersøkelser, disse er påkrevd og kan ikke fjernes eller redigeres. Du kan også legge til dine egne spørsmål som er valgfritt. Maskinnavn feltet er valgfritt og brukes til å kombinere lignende spørsmål slik at de kan vises i statistikk på tvers av emner.</p>

              <div class="embed-survey-questions">
                <div class="embed-survey-questions-wrapper">
                  <h6 v-if="!isSurveySelected"><b>Påkrevd</b></h6>

                  <div>
                    <div
                      class="embed-survey-question"
                      v-for="(question, index) in isSurveySelected ? surveySelected?.questions ?? [] : surveyForm.defaultQuestions"
                      :key="index"
                    >
                      <p>Spørsmål {{ index + 1 }}</p>

                      <label>Tekst</label>
                      <input class="surveyForm" type="text" :value="question.text" disabled />

                      <label>Maskinnavn</label>
                      <input class="surveyForm" type="text" :value="question.machine_name" disabled />
                    </div>
                  </div>
                </div>

                <div class="embed-survey-questions-wrapper" v-if="!isSurveySelected">
                  <h6><b>Valgfritt (maks 3)</b></h6>

                  <div>
                    <div class="embed-survey-question">
                      <p>Spørsmål 1</p>

                      <label for="custom-question-one-value">Tekst</label>
                      <input
                        id="custom-question-one-value"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_one_value"
                        v-model="surveyForm.customQuestionOne.text"
                        placeholder="Tast inn tekst"
                        :disabled="isSurveySelected"
                      />
                      
                      <label for="custom-question-one-key">Maskinnavn</label>
                      <input
                        id="custom-question-one-key"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_one_key"
                        v-model="surveyForm.customQuestionOne.machine_name"
                        placeholder="Tast inn maskinnavn"
                        :disabled="isSurveySelected"
                      />
                    </div>

                    <div class="embed-survey-question" v-if="surveyForm.customQuestionOne.text != '' || surveyForm.customQuestionTwo.text != ''">
                      <p>Spørsmål 2</p>

                      <label for="custom-question-two-value">Tekst</label>
                      <input
                        id="custom-question-two-value"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_two_value"
                        v-model="surveyForm.customQuestionTwo.text"
                        placeholder="Tast inn tekst"
                        :disabled="isSurveySelected"
                      />

                      <label for="custom-question-two-key">Maskinnavn</label>
                      <input
                        id="custom-question-two-key"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_two_key"
                        v-model="surveyForm.customQuestionTwo.machine_name"
                        placeholder="Tast inn maskinnavn"
                        :disabled="isSurveySelected"
                      />
                    </div>

                    <div class="embed-survey-question" v-if="surveyForm.customQuestionTwo.text != '' || surveyForm.customQuestionThree.text != ''">
                      <p>Spørsmål 3</p>

                      <label for="custom-question-three-value">Tekst</label>
                      <input
                        id="custom-question-three-value"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_three_value"
                        v-model="surveyForm.customQuestionThree.text"
                        placeholder="Tast inn tekst"
                        :disabled="isSurveySelected"
                      />

                      <label for="custom-question-three-key">Maskinnavn</label>
                      <input
                        id="custom-question-three-key"
                        class="surveyForm"
                        type="text"
                        maxlength="255"
                        name="custom_question_three_key"
                        v-model="surveyForm.customQuestionThree.machine_name"
                        placeholder="Tast inn maskinnavn"
                        :disabled="isSurveySelected"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="!isSurveySelected && surveyForm.hasError && isTitleEmpty" class='alert alert-info kpasAlert'>Tittel i dashboard kan ikke være tom.</div>
            <div v-if="!isSurveySelected && surveyForm.hasError && isCustomTitleEmpty" class='alert alert-info kpasAlert'>Hvis du ikke ønsker tittel i skjema, fjern avkrysningen i checkbox.</div>
            <div v-if="!isSurveySelected && surveyForm.hasError && !isCustomQuestionsValid" class='alert alert-info kpasAlert'>Spørsmål kan ikke kun ha machine_name, det må også ha en spørsmålstekst.</div>
            <div v-if="!isSurveySelected && surveyForm.submitError" class='alert alert-danger kpasAlert'>Kunne ikke opprette undersøkelse. Prøv igjen.</div>

            <button class="kpas-embed-button" @click="createSurvey" v-if="!isSurveySelected && !hasError" :disabled="surveyForm.isLoading">Opprett undersøkelse</button>

            <a class="kpas-survey-embed kpas-embed-button" :href="urlSurveyMode" v-if="isSurveySelected">Sett inn undersøkelse</a>
            <div class="kpas-embed-button-wrapper">
              <button @click="console.log('surveySelected', surveySelected);surveySelected = null" v-if="isSurveySelected">Lag ny undersøkelse</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="kpas-embed-diplom" v-if="toolToShow == 'diplom'">
    <h4 class="kpas-embed-title">Diplom</h4>

    <p>Velg hvilke logoer som skal vises nederst på diplomet:</p>

    <div class="embed-diplom-logos">
      <div class="embed-diplom-logo" v-for="(logo, index) in diplomaLogos" :key="index">
        <input :id="`embed-diplom-logo-${index}`" type="checkbox" v-model="disploamSelectedLogo" :value="logo"/>

        <label :for="`embed-diplom-logo-${index}`"><img class="diplomaIssuedByImage" :src="'images/' + logo"></label>
      </div>
    </div>

    <a class="kpas-embed-button" :href="urlDiplomaMode">Sett inn Diplom</a>
  </div>
</template>

<script>
import api from '../api';
import LoadingIndicator from "../components/LoadingIndicator"

export default {
  name: "Diploma",
  props: ['courseid', 'coursemodules', 'appurl', 'launchid', 'configdirectory', 'diplomamode', 'statisticsmode', 'dashboardmode', 'surveymode', 'admindashboardmode'],
  components: {
    LoadingIndicator
  },
  data() {
    return {
      toolToShow: null,

      surveySelectedModuleId: null,
      surveys: [],
      surveysIsLoading: false,
      surveySelected: null,
      surveyForm: {
        isLoading: false,
        submitError: false,
        hasError: false,

        useCustomTitle: false,
        useCustomQuestions: false,

        title: "",
        customTitle: "",
        defaultQuestions: [
          { machine_name: "standard_question_1", text: "I hvilken grad har du lært noe gjennom å ha arbeidet med innholdet i denne modulen?" },
          { machine_name: "standard_question_2", text: "I hvilken grad er innholdet i modulen praksisrelevant?" },
          { machine_name: "standard_question_3", text: "I hvilken grad tror du arbeidet med innholdet i denne modulen vil føre til praksisendring?" },
          { machine_name: "standard_question_essay", text: "Har du forslag til forbedringer eller andre kommentarer angående denne modulen?" },
        ],
        customQuestionOne: { machine_name: "", text: "" },
        customQuestionTwo: { machine_name: "", text: "" },
        customQuestionThree: { machine_name: "", text: "" }
      },

      diplomaLogos: [],
      disploamSelectedLogo: [],
    };
  },
  computed: {
    hasSurveys: function () {
      return this.surveys.length > 0;
    },
    isSurveySelected: function () {
      return this.surveySelected != null;
    },

    hasModules: function () {
      return this.coursemodules.length > 0;
    },
    isModuleSelected: function () {
      return this.surveySelectedModuleId != null;
    },

    isTitleEmpty: function () {
      return this?.surveyForm?.title == "";
    },
    isCustomTitleEmpty: function () {
      return this?.surveyForm?.useCustomTitle && this?.surveyForm?.customTitle == "";
    },

    isCustomQuestionsValid: function()  {
      if (this?.surveyForm?.customQuestionOne?.machine_name != "" && this?.surveyForm?.customQuestionOne?.text == "") {
        return false;
      }

      if (this?.surveyForm?.customQuestionTwo?.machine_name != "" && this?.surveyForm?.customQuestionTwo?.text == "") {
        return false;
      }

      if (this?.surveyForm?.customQuestionThree?.machine_name != "" && this?.surveyForm?.customQuestionThree?.text == "") {
        return false;
      }

      return true;
    },

    hasError: function () {
      const validationError = !this.isModuleSelected || this.isTitleEmpty || this.isCustomTitleEmpty || !this.isCustomQuestionsValid;
      if (validationError) {
        this.surveyForm.hasError = true;
      } else {
        this.surveyForm.hasError = false;
      }

      return this.surveyForm.hasError;
    },
    
    urlRoleMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&config_directory=" + this.configdirectory;
    },
    urlStatisticsMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.statisticsmode + "&config_directory=" + this.configdirectory;
    },
    urlDashboardMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.dashboardmode + "&config_directory=" + this.configdirectory;
    },
    urlSurveyMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.surveymode + "&config_directory=" + this.configdirectory + "&survey_id=" + this?.surveySelected?.id;
    },
    urlAdminDashboardMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.admindashboardmode + "&config_directory=" + this.configdirectory;
    },
    urlDiplomaMode: function () {
      var url = this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.diplomamode + "&config_directory=" + this.configdirectory;
      this.disploamSelectedLogo.forEach(function addLogo(logo) {
        url += "&logo[]=" + logo;
      });

      return url;
    }
  },
  created() {
    this.getLogos()
  },
  methods: {
    async getSurveys() {
      const courseId = this.courseid;
      const moduleId = this.surveySelectedModuleId;
      this.surveyForm.isLoading = true;

      await api.get('/surveys', {
        cookie: window.cookie,
        params: { course_id: courseId, module_id: moduleId }
      }).then((response) => {
        this.surveys = response?.data?.result ?? [];
        this.surveyForm.isLoading = false;
        console.log("GET_SURVEYS_RESPONSE", this.surveys);
      }).catch((error) => {
        this.surveys = [];
        this.surveyForm.isLoading = false;
        console.log("GET_SURVEYS_RESPONSE_ERROR", error);
      });
    },
    updateSelectedModule(selectedModule){
      console.log("SELECTED_MODULE", selectedModule);

      this.surveySelectedModuleId = selectedModule?.id ?? null;
      if (this.surveySelectedModuleId == null) {
        this.surveys = [];
        return;
      }

      this.getSurveys();
    },
    async createSurvey(){
      if (this.hasError) return;
      if (this.surveyForm.isLoading) return;
      this.surveyForm.submitError = false;

      const questions = [];
      if (this.surveyForm.customQuestionOne.text != "") questions.push(this.surveyForm.customQuestionOne);
      if (this.surveyForm.customQuestionTwo.text != "") questions.push(this.surveyForm.customQuestionTwo);
      if (this.surveyForm.customQuestionThree.text != "") questions.push(this.surveyForm.customQuestionThree);

      const response = await api.post('survey/create', {
        cookie: window.cookie,
        course_id: this.courseid,
        module_id: this.surveySelectedModuleId,
        title: this.surveyForm.useCustomTitle ? this.surveyForm.customTitle : null,
        title_internal: this.surveyForm.title,
        questions: questions
      })

      console.log("CREATE_SURVEY_RESPONSE", response.data);
      if (response.data.status != 200) {
        this.surveyForm.submitError = true;
        return; 
      }

      await this.getSurveys();
      this.surveySelected = this.surveys?.find(survey => survey?.id == response.data.result) ?? null;
    },
    async getLogos() {
        const response = await api.get('/diploma/logolist');
        this.diplomaLogos = response.data.result;
        console.log("GET_DIPLOMA_LOGOS_RESPONSE", response.data);
    },
  },
};
</script>

<style>
  subsection {
    width: fit-content;
  }
  section {
    width: 80%;
    align-content: flex-start;
  }
  #surveyForm {
    padding: 0.5em;
    margin: 0.5em;
  }
  #createButton {
    padding: 0.5em;
    margin: 0.2em;
  }
  a {
    font-size: large;
  }
</style>
