@extends('layouts.app')
<template>
  <div v-if="ready" >
    <v-select
      class="selector"
      :disabled="!coursemodules.length"
      label="name"
      :options="coursemodules"
      placeholder="Velg modul"
      @update:modelValue="updateModule"
    ></v-select>
    <div v-if="currentGroupId && completed_count_item.length" >
      <section class="completed">
        <table>
          <tr>
            <th><b>Side</b></th>
            <th><b>Antall fullført</b></th>
          </tr>
          <tr v-for="item in completed_count_item">
            <td>{{ item.title }}</td>
            <td>{{ item.count }}</td>
          </tr> 
        </table>
      </section>
    </div>

    <div class = "survey-data" v-if="module_surveys.length">
      <v-select v-if="module_surveys.length > 1"
      class="selector"
      label="title_internal"
      :options="module_surveys"
      placeholder="Velg survey"
      @update:modelValue="updateSurvey"
      ></v-select>

      <h2 class="title">{{view_survey.title_internal}}</h2>

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
    <div v-else>
      <span class="ml-3"> Denne modulen har ikke survey. </span>
    </div>
  </div>

</template>

<script>
import api from "../api";
export default {
  name: "AdminDashboardView",
  props: {
    settings: {},
    likert5ops: {},
    coursemodules: []
  },
  data(){
    return {
      survey_data: null,
      module_surveys: [], 
      view_survey: null,
      currentGroupId: 0,
      ready: false,
      modules_statistics: null,
      completed_count_item: [], 
      current_module: 0,
      
    }
  },
  methods: {
    updateModule(value){
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
        if(this.currentGroupId == null  || this.currentGroupId == 0){
          url = "/survey/course/" + this.settings.custom_canvas_course_id;
        }
        else {
          url = "/survey/course/" + this.settings.custom_canvas_course_id + "?group=" + this.currentGroupId;
        }
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.survey_data = apiResult.data.result;
        this.updateModule(this.coursemodules[0]);

      } catch(e)
      {
        console.log("Could not get survey data.", e);
      }
    },
    async getModulesStatistics(){
      try{
        if(this.currentGroupId != null || this.currentGroupId != 0){
          let url = "/course/" + this.settings.custom_canvas_course_id + "/modules?group=" + this.currentGroupId;
          
          const apiResult = await api.get(url, {
            params: { cookie: window.cookie }
          });
          this.modules_statistics = JSON.parse(apiResult.data.result);
          console.log(this.modules_statistics)

        }
      }catch(e)
      {
        console.log("Could not get module data.", e);
      }
    },
    updateFinnishCount(){
      this.completed_count_item = []
      let module = this.modules_statistics.find(e => e.canvas_id == this.current_module);
      let moduleitems = module.module_items.sort((a, b) => a.position - b.position)
      for(const item of moduleitems){
        if(this.currentGroupId !=  0 || this.currentGroupId != null) {
          if(item.user_groups.length == 1){
            let obj = {"title" : item.title, "count" : item.user_groups[0].count}
            this.completed_count_item.push(obj)
            console.log(obj)
          }
          else{
            let obj = {"title" : item.title, "count" : 0}
            this.completed_count_item.push(obj)
            console.log(obj)
          }
        }
      }
      
    }
  },
  async created(){
    await this.getModulesStatistics();
    await this.getSurveyData();
    this.ready = true;
  }
  
};
</script> 

<style>
 td{
  padding: .5em;
 }

 .completed{
  overflow-y: scroll;
  height: 18em;
 }
</style>
