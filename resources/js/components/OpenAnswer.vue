
<template>
    <h2 v-html="this.questionText"></h2>
   <div v-for="(openAnswer, i) in openAnswers">
      <div class="note">
        <p class="date"> {{formatdate(openAnswer.submission.submitted)}} 
        <p class="responseText" v-html="openAnswer.value "></p> </p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "OpenAnswer",
    
    props: {
      openAnswers: null,
      questionText: null,
      responseCount: null
    },
    methods: {
      formatdate(date) {
        console.log(date)
        let months = ['januar', 'februar', 'mars', 'april', 'mai', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'desember'];
        let newDate = new Date(date)
        let dateFormated = newDate.getDate() + ' ' + months[newDate.getMonth()] + ' ' + newDate.getFullYear();
        console.log(dateFormated)
        return dateFormated
      }
    } 
  }
  </script>
  
  <style>
    .date {
      font-size: 1em;
    }
    .responseText {
      font-size: 1.5rem;
    }
    .note {
      padding-bottom: .5em;
      padding-top: .5em;
    }
  </style>