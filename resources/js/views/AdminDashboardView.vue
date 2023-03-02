@extends('layouts.app')
<template>
  <div v-if="ready" >
    <DashboardGroupSelect 
      :settings=this.settings
      :categories=this.categories
      @update="updateGroupId"
    />
    <section id="student-count">
      <h3>Antall brukere: {{ studentCount }}</h3>
    </section>
    
  
    <v-select
      class="selector"
      :disabled="!coursemodules.length"
      label="name"
      :options="coursemodules"
      placeholder="Velg modul"
      @update:modelValue="updateModule"
    ></v-select>

    <div v-if="completed_count_item.length && current_module" >
      <h3>Markert som fullført: </h3>
      <horizontal-bar-chart class="completed" :data="completed_count_item"> </horizontal-bar-chart>
    </div> 

    <div class = "survey-data" v-if="module_surveys.length && current_module">

        <v-select v-if="module_surveys.length > 1"
        class="selector"
        label="title_internal"
        :options="module_surveys"
        placeholder="Velg survey"
        @update:modelValue="updateSurvey"
        ></v-select>


      <h3 class="title">{{view_survey.title_internal}}</h3>

      <section class="grouped" >
        <grouped-bar-chart id="view_module.course_id" :data="view_survey.questions.slice(0,3)" :likert5ops="this.likert5ops"></grouped-bar-chart>
      </section>

      <section class="barview" v-if="view_survey.questions.length > 4">
        <div v-for="(question, i) in view_survey.questions.slice(3)" :key="i">
          <bar-chart v-if="question.question_type == 'likert_scale_5pt'" :id="'q' + i" :data="question" :svgWidth=600 :svgHeight=400></bar-chart>
        </div>
      </section>

      <section class="feedback">
        <div v-for="(question, i) in view_survey.questions" :key="i">
          <open-answer v-if="question.question_type === 'essay'" :openAnswers="question.submission_data" :questionText="question.text"></open-answer>
        </div>
      </section>
    </div>
    <div v-else-if="!module_surveys.length && current_module">
      <h3> Denne modulen har ikke survey. </h3>
    </div>
    <div v-else>
      <h3> Velg modul for å se survey resultater. </h3>
    </div>
  </div>
  <div v-else>
    <span class="ml-3">Laster Dashboard. <div class="spinner-border text-success"></div></span>
  </div>

</template>

<script>
import api from "../api";
import DashboardGroupSelect from "../components/DashboardGroupSelect";

export default {
  name: "AdminDashboardView",
  components: {
    DashboardGroupSelect,
  },
  data() {
    return {
      studentCount: null,
      groupId: null,
      categories: null,
      survey_data: null,
      module_surveys: [], 
      view_survey: null,
      ready: false,
      modules_statistics: null,
      completed_count_item: [], 
      current_module: null,
    }
  },
  props: {
    settings: {},
    likert5ops: {},
    coursemodules: [],
  },
  methods: { 
     async getStudentCount() {
      try {
        let url;
        if (this.groupId) { 
          url = "/group/" + this.groupId + '/count';
        } else { 
          url = "/course/" + this.settings.custom_canvas_course_id + '/count';
        };

        const response = await api.get(url, {
          params: { cookie: window.cookie }
        });

        this.studentCount = await response.data.result;
      } catch(e) {
        console.error("Could not get student count.", e);
      }
    },
    async getGroupCategories() { // TODO: refactor this method to GroupSelector
      try {
        const response = await api.get('/group/user', {
          params: {
            cookie: window.cookie,
          }
        });
        this.categories = response.data.result;
      } catch(e){
        console.error("Could not get group categories.", e)
      }
    },
    async updateGroupId(value) {
      this.groupId = value;
      await this.getStudentCount();
      await this.getModulesStatistics();
      await this.getSurveyData();
    },
    updateModule(value){
      if(value == null){
        this.current_module = null
        return;
      }
      this.module_surveys = this.survey_data.filter(e => e.module_id == value.id)
      if(this.module_surveys.length > 0){
        this.view_survey = this.module_surveys[0];
      }
      this.current_module = value.id;
      this.updateFinnishCount()
    },
    updateSurvey(value){
      this.view_survey = value;
    },
    async getSurveyData() {
      try {
        let url;
        if(!this.groupId){
          url = "/survey/course/" + this.settings.custom_canvas_course_id;
        }
        else {
          url = "/survey/course/" + this.settings.custom_canvas_course_id + "?group=" + this.groupId;
        }
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.survey_data = apiResult.data.result;
        if(this.current_module) {
          this.view_survey = this.survey_data.filter(e => e.module_id == this.current_module)[0]
        }
      } catch(e)
      {
        console.log("Could not get survey data.", e);
      }
    },
    async getModulesStatistics(){
      try{
        if(this.groupId){
          let url = "/course/" + this.settings.custom_canvas_course_id + "/modules?group=" + this.groupId;
          console.log(url)
          const apiResult = await api.get(url, {
            params: { cookie: window.cookie }
          });
          this.modules_statistics = JSON.parse(apiResult.data.result);
          this.updateFinnishCount()

        }
        else{
          let url = "/course/" + this.settings.custom_canvas_course_id + "/modules/count";
          const apiResult = await api.get(url, {
            params: { cookie: window.cookie }
          });
          this.modules_statistics = JSON.parse(apiResult.data.result);
          this.updateFinnishCount()
        }
      }catch(e)
      {
        console.log("Could not get module data.", e);
      }
    },
    updateFinnishCount(){
      this.completed_count_item = []
      if (!this.current_module){
        return;
      }
      let module = this.modules_statistics.find(e => e.canvas_id == this.current_module);
      let moduleitems = module.module_items.sort((a, b) => a.position - b.position)
      for(const item of moduleitems){
        if(this.groupId) {
          if(item.user_groups.length == 1){
            let obj = {"title" : item.title, "count" : item.user_groups[0].count}
            this.completed_count_item.push(obj)
          }
          else{
            let obj = {"title" : item.title, "count" : 0}
            this.completed_count_item.push(obj)
          }
        }
        else {
            let obj = {"title" : item.title, "count" : item.total_completed}
            this.completed_count_item.push(obj)
        }
      }      
    }
  },
 
  async created() {
    await this.getGroupCategories();
    await this.getStudentCount();
    await this.getModulesStatistics();
    await this.getSurveyData();
    this.ready = true;
  },
};
</script> 

<style>
 td{
  padding: 1em;
 }
 
 h3 {
  padding: .5em;
 }

 .selector{
  width: 40%;
 }
 
 .survey-data{
  width: 100%;
 }

 .completed{
  overflow-y: scroll;
  height: 36em;
  margin: 1em;
  padding: 1em;
 }

 template{
  background-color: #eaeaea;
 }
</style>
