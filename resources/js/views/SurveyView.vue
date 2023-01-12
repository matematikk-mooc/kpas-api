@extends('layouts.app')
<template>
  <h2>{{survey.title_form}}</h2>

  <form class="survey-form" @submit.prevent="submit">

    <div v-for="question in questions" :key="question.id" class="survey-question">

      <fieldset v-if="question.question_type == 'likert_scale_5pt'">
        <legend class="survey-question-title">{{question.text}}</legend>
        <label class="survey-likert-scale-option" v-for="(value, i) in likert5ops" :key="i">
          <input type="radio" :id="'likert_'+question.id+'_'+i" :name="'likert_'+question.id" :value="i" v-model="answers[question.id]">
            <span>{{value}}</span>
        </label>
      </fieldset>

      <div v-if="question.question_type == 'essay'">
        <label class="survey-question-title" :for="'essay_' + question.id">{{question.text}}</label>
        <textarea class="form-control"
                  rows="4"
                  maxlength="2000"
                  :id="'essay_' + question.id"
                  v-model="answers[question.id]"></textarea>
      </div>

    </div>

    <button class="btn btn-primary" type="submit">Send inn</button>
  </form>

</template>

<script>
export default {
  name: "SurveyView",
  data() {
    return {
      survey: {},
      questions: [],
      likert5ops: {},
      answers: {},
    };
  },
  props: {
    survey: {},
    questions: [],
    likert5ops: {}
  },
  methods: {
    submit() {
      // todo: implement
      alert(JSON.stringify(this.likert5ops))
    }
  },
}



</script>
