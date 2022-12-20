<template>
  <h1 v-html="this.questionText"></h1>
  <p>{{this.responseCount}} svar</p>
 <div v-for="(openAnswer, i) in openAnswersFiltered">
    <div class="note">
      <p class="date"> {{formatdate(openAnswer.submission_time)}} 
      <p class="responseText" v-html="openAnswer.answer"></p> </p>
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
  data() {
    return {
      filtered_list: []
    }
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
  }, 
  computed:{
    openAnswersFiltered: function() { 
      let filtered  = this.openAnswers.filter((value, index, self) => {
      return self.findIndex(v => v.answer === value.answer && v.submission_time === value.submission_time) === index;
      })
      console.log(filtered)
      return filtered
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