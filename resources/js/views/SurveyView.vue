@extends('layouts.app')
<template>
  <h2 v-if="survey.title_form">{{survey.title_form}}</h2>

  <div v-if="errorText" class='alert alert-danger kpasAlert'>{{errorText}}</div>

  <form class="survey-form" @submit.prevent="submit" v-if="!isSubmitting && !existing_submission">

    <div v-for="question in questions" :key="question.id" class="survey-question">

      <fieldset v-if="question.question_type == 'likert_scale_5pt'">
        <legend class="survey-question-title">{{question.text}}</legend>
        <label class="survey-likert-scale-option" v-for="(value, i) in likert5ops" :key="i">
          <input type="radio"
                 :id="'likert_'+question.id+'_'+i"
                 :name="'likert_'+question.id"
                 :required="question.required"
                 :value="i" v-model="answers[question.id]">
          <span>{{value}}</span>
        </label>
      </fieldset>

      <div v-if="question.question_type == 'essay'">
        <label class="survey-question-title" :for="'essay_' + question.id">{{question.text}}</label>
        <textarea class="form-control"
                  rows="4"
                  :id="'essay_' + question.id"
                  maxlength="2000"
                  :required="question.required"
                  v-model="answers[question.id]"></textarea>
      </div>

    </div>
    <button class="btn btn-primary" type="submit">Send inn</button>
  </form>

  <div id="displaySubmission" class="survey-existing-response" v-if="existing_submission">
    <h2>Takk for din tilbakemelding!</h2>
    <br>
    <h3>Dine svar</h3>
    <div v-for="question in existing_submission.survey.questions" class="question">
      <h4>{{question.text}}</h4>
      <div v-if="!question.submission_data || question.submission_data.length === 0">—</div>
      <div v-else>
        <p v-if="question.question_type === 'likert_scale_5pt'">{{likert5ops[question.submission_data[0].value]}}</p>
        <p v-if="question.question_type === 'essay'">{{question.submission_data[0].value}}</p>
      </div>
    </div>
    <button class="btn btn-primary" @click="deleteSubmission()">Fjern svar</button>
  </div>

  <span class="ml-3" v-if="isSubmitting">Sender <div class="spinner-border text-success"></div></span>

</template>

<script>
import api from "../api";

export default {
  name: "SurveyView",
  data: function() {
    return {
      survey: this.survey,
      questions: this.questions,
      likert5ops: this.likert5ops,
      answers: {},
      errorText: undefined,
      isSubmitting: false
    };
  },
  props: {
    survey: {},
    questions: [],
    likert5ops: {},
    existing_submission: {}
  },
  methods: {
    async submit() {
      this.isSubmitting = true
      this.errorText = undefined

      let err = this.validate()
      if (err) {
        this.errorText = err
        this.isSubmitting = false
        return
      }

      // trim all answers
      for (let key in this.answers) {
        this.answers[key] = this.answers[key].trim()
      }

      // remove empty answers
      let answers = []
      for (let key in this.answers) {
        if (this.answers[key] !== null && this.answers[key] !== undefined && this.answers[key] !== '') {
          answers.push({
            question_id: key,
            value: this.answers[key]
          })
        }
      }

      let response = undefined
      try {
        response = await api.post('survey/' + this.survey.id + '/submission/create', {
          cookie: window.cookie,
          answers: answers
        })
      } catch (e) {
        if (e.response && e.response.status === 409) {
          this.errorText = "Du har allerede svart på denne undersøkelsen."
        } else {
          this.errorText = "Beklager - det oppstod en feil. Prøv igjen senere."
        }
        this.isSubmitting = false
        return
      }

      if (!response || response.status !== 200) {
        this.isSubmitting = false
        this.errorText = "Beklager - det oppstod en feil. Prøv igjen senere."
        return
      }

      location.reload()
    },

    validate() {
      for (let i = 0; i < this.questions.length; i++) {
        let question = this.questions[i]
        let answer = this.answers[question.id]

        // Check if question is required
        if (question.required) {
          if (answer == null || answer.length === 0) {
            return 'Du må svare på spørsmålet: ' + question.text
          }
        }

        // Check if answer is too long
        if (question.question_type === 'essay') {
          if (answer !== undefined && answer.length > 2000) {
            return 'Svaret på spørsmålet "' + question.text + '" er for langt. Maks 2000 tegn.'
          }
        }
      }
      return undefined
    },

    deleteSubmission() {
      if (confirm('Er du sikker på at du vil slette svaret ditt?')) {
        api.delete('survey/' + this.survey.id + '/submission/delete', {
          data: {
            cookie: window.cookie
          }
        }).then(() => {
          location.reload()
        })
      }
    }
  },
}
</script>

<style scoped>

.btn-primary {
  cursor: pointer;
  position: relative;
  color: #303030;
  background: white;
  border: 0.125rem solid #303030;
  border-radius: 0.1875rem;
  font-weight: 700;
  line-height: 1;
  display: flex;
  align-items: center;
  bottom: -0.05rem;
}

.btn-primary:hover {
  border: 0.125rem solid #00468e;
}

.survey-question-title{
  display: inline;
}
</style>