@extends('layouts.app')
<template>
  <div>
    <h1>Rolle- og gruppeverktøy</h1>
    <a :href="urlRoleMode">Sett inn Rolle- og gruppeverktøy</a>
    
    <h1>Statistikk</h1>
    <a :href="urlStatisticsMode">Sett inn statistikkverktøy</a>

    <h1>Quiz</h1>
    <a :href="urlQuizMode">Sett inn quizverktøy</a>

    <h1>Survey</h1>
    <section>
      <subsection>
        <label for="title">
          Tittel:
          <input class="surveyForm" type="text" name="title" v-model="title"/>
        </label>
      </subsection>
      <subsection>
        <label for="title_internal">
          Intern tittel:
          <input class="surveyForm" type="text" name="title_internal" v-model="title_internal"/>
        </label>
      </subsection>
      <p>Valgfrie spørsmål</p>
      <subsection>
        <label for="question1">
          Spørsmål 1:
          <input class="surveyForm" type="text" name="question1" v-model="question1"/>
        </label>
        </subsection>
      <subsection>
        <label for="question2">
          Spørsmål 2:
          <input class="surveyForm" type="text" name="question2" v-model="question2"/>
        </label>
      </subsection>
      <subsection>
        <label for="question3">
          Spørsmål 3:
          <input class="surveyForm" type="text" name="question3" v-model="question3"/>
        </label>
        </subsection>
        <button @click="setTitle">Opprett survey</button>
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
  props: ['courseid', 'appurl', 'launchid', 'configdirectory', 'diplomamode', 'statisticsmode', 'quizmode', 'surveymode'],    
  data() {
    return {
      logoList: [],
      logoSelected: [],
      title: '', 
      title_internal: '',
      question1: '', 
      question2: '', 
      question3: ''  
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
    urlQuizMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.quizmode + "&config_directory=" + this.configdirectory;
    },
    urlSurveyMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.surveymode + "&config_directory=" + this.configdirectory;
    }
  },  
  methods: {
    async getLogoList() {
        const response = await api.get('/diploma/logolist');
        this.logoList = response.data.result;
    },
    async fetchLogoList() {
    },
    async setTitle(){
      console.log("Title: " + this.title);
      console.log("Q1: " + this.question1);
      console.log("Q2: " + this.question2);
      console.log("Q3: " + this.question3);
      console.log(this.courseid)
      
      //do input validation here

      const response = await api.post('survey/create', {
        cookie: window.cookie, 
        courseid: this.courseid,
        title: this.title, 
        title_internal: this.title,
        question1: this.question1, 
        question2: this.question2, 
        question3: this.question3
      })

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

</style> 