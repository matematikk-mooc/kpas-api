@extends('layouts.app')
<template>
  <div>
    <h1>Rolle- og gruppeverktøy</h1>
    <a :href="urlRoleMode">Sett inn Rolle- og gruppeverktøy</a>

    <h1>Statistikk</h1>
    <a :href="urlStatisticsMode">Sett inn statistikkverktøy</a>
    <h1>Dashboard</h1>
    <a :href="urlDashboardMode">Sett inn dashboard</a>
    <h1>Admin/Udir dashboard</h1>
    <a :href="urlAdminDashboardMode">Sett inn admin/Udir dashboard</a>
    <h1>Survey</h1>
    <section role="form" class="embed-survey-form">
      <div class="subsection">
        <v-select
          class="selector"
          :disabled="!coursemodules.length"
          :options="coursemodules"
          label="name"
          placeholder="Velg modul"
          @update:modelValue="updateModule"
        >
        </v-select>
        <label>
          <input type="checkbox" class="surveyForm" v-model="add_form_title"/>
          Tittel i skjema
        </label>
        <div v-if="add_form_title">
          <label for="title">
            Tittel, skjema
            <input class="surveyForm" type="text" maxlength="255" name="title" v-model="title"/>
          </label>
        </div>
      </div>
      <div class="subsection">
        <label for="title_internal">
          Tittel i dashboard:
          <input class="surveyForm" type="text" maxlength="255" name="title_internal" v-model="title_internal"/>
        </label>
      </div>
      <br/>
      Valgfrie spørsmål (disse har spørsmålstypen: 5-punkt skala):
      <div class="subsection">
        <label for="question1">
          Spørsmål 1:
          <input class="surveyForm" type="text" maxlength="255" name="question1text" v-model="question1.text" placeholder="Spørsmålstekst"/>
          <input class="surveyForm" type="text" maxlength="255" name="question1name" v-model="question1.machine_name" placeholder="machine_name"/>
        </label>
        </div>
      <div class="subsection">
        <label for="question2">
          Spørsmål 2:
          <input class="surveyForm" type="text" maxlength="255" name="question2text" v-model="question2.text" placeholder="Spørsmålstekst"/>
          <input class="surveyForm" type="text" maxlength="255" name="question2name" v-model="question2.machine_name" placeholder="machine_name"/>
        </label>
      </div>
      <div class="subsection">
        <label for="question3">
          Spørsmål 3:
          <input class="surveyForm" type="text" maxlength="255" name="question3" v-model="question3.text" placeholder="Spørsmålstekst"/>
          <input class="surveyForm" type="text" maxlength="255" name="question3name" v-model="question3.machine_name" placeholder="machine_name"/>
        </label>
      </div>

      <div v-if="emptyTitleForm" class='alert alert-danger kpasAlert'>Hvis du ikke ønsker tittel i skjema, fjern avkrysningen i checkbox.</div>
      <div v-if="emptyTitleInternal" class='alert alert-danger kpasAlert'>Tittel i dashboard kan ikke være tom.</div>
      <div v-if="emptyModuleSelected" class='alert alert-danger kpasAlert'>Modul kan ikke være tom.</div>
      <div v-if="emptyQuestionText" class='alert alert-danger kpasAlert'>Spørsmål kan ikke kun ha machine_name, det må også ha en spørsmålstekst.</div>
      <div v-if="surveyCreated" class='alert alert-success kpasAlert'>Survey opprettet! Den kan nå settes inn i LTI.</div>
      <div v-if="couldNotCreateSurvey" class='alert alert-danger kpasAlert'>Kunne ikke opprette survey. Prøv igjen.</div>


      <br/>
      <button id="createButton" @click="createSurvey">Opprett survey</button>
    </section>
    <a :href="urlSurveyMode">Sett inn survey</a>
    <h1>Diplom</h1>
    Velg hvilke logoer som skal vises nederst på diplomet:

    <div v-for="logo in logoList">
        <label><img class="diplomaIssuedByImage" :src="'images/' + logo"></label>
        <input type="checkbox" v-model="logoSelected" :value="logo"/>
    </div>
    <a :href="urlDiplomaMode">Sett inn Diplom</a>
  </div>
</template>

<script>
import api from '../api';

export default {
  name: "Diploma",

  props: ['courseid', 'coursemodules', 'appurl', 'launchid', 'configdirectory', 'diplomamode', 'statisticsmode', 'dashboardmode', 'surveymode', 'admindashboardmode'],
  data() {
    return {
      logoList: [],
      logoSelected: [],
      add_form_title: false,
      title: '',
      title_internal: '',
      selectedModule: 0,
      question1: {'text' : '', 'machine_name' : ''},
      question2: {'text' : '', 'machine_name' : ''},
      question3: {'text' : '', 'machine_name' : ''},
      survey_id: -1,
      emptyTitleForm: false,
      emptyTitleInternal: false,
      emptyQuestionText: false,
      surveyCreated: false,
      couldNotCreateSurvey: false,
      emptyModuleSelected: false
    };
  },
  computed: {
    urlRoleMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&config_directory=" + this.configdirectory;
    },
    urlDiplomaMode: function () {
      var url = this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.diplomamode + "&config_directory=" + this.configdirectory;
      this.logoSelected.forEach(function addLogo(logo) {
        url += "&logo[]=" + logo;
      });
    return url;
    },
    urlStatisticsMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.statisticsmode + "&config_directory=" + this.configdirectory;
    },
    urlDashboardMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.dashboardmode + "&config_directory=" + this.configdirectory;
    },
    urlSurveyMode: function () {
      if (this.survey_id != -1){
        return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.surveymode + "&config_directory=" + this.configdirectory + "&survey_id=" + this.survey_id;
      }
    },
    urlAdminDashboardMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.admindashboardmode + "&config_directory=" + this.configdirectory;
    }

  },
  methods: {
    async getLogoList() {
        const response = await api.get('/diploma/logolist');
        this.logoList = response.data.result;
    },
    async fetchLogoList() {
    },
    updateModule(module){
      if(module == null){
        this.selectedModule = 0; 
        this.emptyModuleSelected = true
        return;
      }
      this.selectedModule = module.id;
      this.emptyModuleSelected = false;
    },

    async createSurvey(){

      if(this.title_internal == ""){
        this.emptyTitleInternal = true;
        return;
      }
      if (this.title == "" && this.add_form_title){
        this.emptyTitleForm = true;
        return;
      }
      if (this.selectedModule == 0){
        this.emptyModuleSelected = true;
        return;
      }
      this.emptyTitleInternal = false;
      this.emptyTitleForm = false;
      this.emptyModuleSelected = false;

      var questions = [];
      questions.push(this.question1);
      questions.push(this.question2);
      questions.push(this.question3);

      for(var i = 0; i < questions.length; i++){
        if(questions[i].machine_name != "" && questions[i].text == ""){
          this.emptyQuestionText = true;
          return;
        }
      }
      this.emptyQuestionText = false;

      const response = await api.post('survey/create', {
        cookie: window.cookie,
        course_id: this.courseid,
        module_id: this.selectedModule,
        title: this.add_form_title ? this.title : null,
        title_internal: this.title_internal,
        questions: questions
      })
      if(response.data.status == 200) {
        this.survey_id = response.data.result;
        this.surveyCreated = true;
      }
      else{
        this.couldNotCreateSurvey = true;
        return;
      }

      var surveyElements = document.getElementsByClassName("surveyForm");
      Array.from(surveyElements).forEach(function(elem) {
        elem.value = "";
      });


    }
  },
  async created() {
      await Promise.all([this.getLogoList()]);
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
