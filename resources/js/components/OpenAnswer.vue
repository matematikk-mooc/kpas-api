
<template>
  <h3 v-html="this.questionText"></h3>
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
        let months = ['januar', 'februar', 'mars', 'april', 'mai', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'desember'];
        let newDate = new Date(date)
        let dateFormated = newDate.getDate() + ' ' + months[newDate.getMonth()] + ' ' + newDate.getFullYear();
        return dateFormated
      }
    } 
  }
  </script>
  
  <style>
    .date {
      font-size: 1em;
      padding: .5em 0 .5em 2em;
    }
    .responseText {
      font-size: 1.5em;
      padding: .5em 0 .5em 0;
    }
    .note {
      padding: .5em 0 .5em 0;
    }
  </style>
  