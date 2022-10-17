
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
        <D3BarChart :datum="question.answers" :config="chart_config"></D3BarChart>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../api";
import { D3BarChart } from 'vue-d3-charts';
//https://saigesp.github.io/vue-d3-charts/#/barchart
export default {
  name: "QuizStatisticsView",
  components: {
    D3BarChart,
  },
  data() {
    return {
      quiz_data : null,
      chart_config: {
        key: 'text',
         values: ['responses'],
        axis: {
          yTicks: 5
        },
        color: {
          default: '#222f3e',
          current: '#41B882'
        },
        orientation: 'horizontal',
        margin: {
          top: 20,
          bottom: 20, 
          right: 40, 
          left: 100
        }
      }

    };
  },
  methods: {
      iframeresize() {
      this.$nextTick(function () {
        var h = $("body").height() + 120;
        parent.postMessage(
          JSON.stringify({ subject: "lti.frameResize", height: h }),
          "*"
        );
      });
    },
    async getQuizData() {
      try {
        var courseId = this.course_id;
        var url = "/course/" + courseId + "/quizzes";
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
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => { 
      self.iframeresize();
    }
    //this.getQuizData();
  }
};
</script> 