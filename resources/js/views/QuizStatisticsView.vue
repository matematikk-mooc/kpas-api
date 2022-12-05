@extends('layouts.app')
<template>
  <div>
    <h1>Quiz statistikk</h1>
    <input v-on:keyup.enter="getQuizData" type="number" placeholder="Kompetansepakke id" v-model="course_id"/>
    <button @click="getQuizData">Finn kompetansepakkens quizzer</button>
     <div v-for="(quiz, i) in quiz_data" :key="i">    
      <h1>{{quiz.title}}</h1>
      <p>Antall besvarelser: {{quiz.submission_statistics[0].unique_count}}</p> 
      <div v-for="(question, i) in quiz.question_statistics" :key="i">
        <h2 v-html="question.question_text"></h2>
        <p>Antall responser: {{question.responses}}</p>
        <bar-chart :id="'q' + question.canvas_id" :data="question.answers" :svgWidth="600" :svgHeight="400"></bar-chart>
      </div>
    </div>
  </div> 
</template>

<script>
import api from "../api";
//import BarChart from "../components/charts/BarChart.vue";
export default {
  name: "QuizStatisticsView",
  data() {
    return {
      quiz_data : null,
    };
  },
  props: {
    settings: {}
  },
  methods: {
      iframeresize() {
      this.$nextTick(function () {
        parent.postMessage(
          JSON.stringify({ subject: "lti.frameResize", height: 500 }),
          "*"
        );
      });
    },
    async getQuizData() {
      try {
        var courseId = this.course_id
        var url = "/course/" + courseId + "/quizzes?format=json";
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.quiz_data = JSON.parse(apiResult.data.result);
      } catch(e)
      {
        console.log("Could not get quiz data.");
      }
    },
  },
  async created() {
    this.iframeresize();
    this.course_id = this.settings.custom_canvas_course_id
    console.log(this.settings)
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => { 
      self.iframeresize();
    }
    this.getQuizData();
  }
};
</script> 