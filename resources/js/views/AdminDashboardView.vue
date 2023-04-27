@extends('layouts.app')
<template>
  <div ref="ltiView" class="dashboard" v-if="allowed && ready && connectedToParent" >
    <h1>Oversikt over kompetansepakken</h1>

    <section class="filtering-section">
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
      :clearable="true"
      placeholder="Velg modul"
      @update:modelValue="updateModule"
      ></v-select>

      <v-select v-if="module_surveys.length > 1"
        class="selector"
        label="title_internal"
        :clearable="true"
        :options="module_surveys"
        placeholder="Velg survey"
        @update:modelValue="updateSurvey"
        ></v-select>

    </section>
    <h2 v-if="completed_count_item.length && current_module" class="title">Markert som fullført </h2>
    <section class="completed-section" v-if="modules_statistics_per_date">
      <line-chart :data="modules_statistics_per_date"></line-chart>
    </section>
    <section class="completed-section" v-if="completed_count_item.length && current_module" >
      <horizontal-bar-chart class="completed" :data="completed_count_item"> </horizontal-bar-chart>
    </section>

    <h2 v-if="view_survey" class="title">{{view_survey.title_internal}}</h2>

    <section class="grouped" v-if="module_surveys.length && current_module && view_survey">
      <grouped-bar-chart :id="view_survey.id" :data="view_survey.questions.slice(0,3)" :likert5ops="this.likert5ops"></grouped-bar-chart>
    </section>

    <section class="barview" v-if="view_survey && view_survey.questions.length > 4 && module_surveys.length && current_module">
      <div v-for="(question, i) in view_survey.questions.slice(3)" :key="i">
        <bar-chart v-if="question.question_type == 'likert_scale_5pt'" :id="'q' + i" :data="question" :svgWidth=600 :svgHeight=400></bar-chart>
      </div>
    </section>

    <section class="feedback" v-if="module_surveys.length && current_module && view_survey">
      <div v-for="(question, i) in view_survey.questions" :key="i">
        <open-answer v-if="question.question_type === 'essay'" :openAnswers="question.submission_data" :questionText="question.text"></open-answer>
      </div>
    </section>

    <section class="no-survey" v-else-if="!module_surveys.length && current_module">
      <h3> Denne modulen har ikke brukerundersøkelse. </h3>
    </section>

    <section class="no-module" v-else>
      <h3> Velg modul for å se statistikk og resultater. </h3>
    </section>
  </div>
  <div v-else-if="!allowed">
    <h3>Du har ikke rettigheter til å se denne siden, dersom du er ansatt i Udir kan du ta kontakt ved kompetansesupport@udir.no</h3>
  </div>
  <div v-else>
    <span class="ml-3">Laster Dashboard. <div class="spinner-border text-success"></div></span>
  </div>

</template>

<script>
  import api from "../api";
  import DashboardGroupSelect from "../components/DashboardGroupSelect";
  import "vue-select/dist/vue-select.css";

  export default {
    name: "AdminDashboardView",
    components: {
      DashboardGroupSelect,
    },
    props: {
      settings: {},
      likert5ops: {},
      coursemodules: [],
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
        modules_statistics_per_date: null,
        completed_count_item: [],
        current_module: null,
        connectedToParent: false,
        allowed: false,
      }
    },
    methods: {

      iframeresize() {
        this.$nextTick(function () {
          var h = this.$refs.ltiView.clientHeight + 50
          console.log("view height")
          console.log(h)
          parent.postMessage(
          JSON.stringify({ subject: "lti.frameResize", height: h }),
          "*"
          );
        });
      },
      postMessageToParent(subject) {
        const message = {
          subject: subject
        };
        window.parent.postMessage(JSON.stringify(message), "*");
      },
      getBgColor() {
        this.postMessageToParent('kpas-lti.getBgColor');
      },
      connectToParent() {
        if(this.connectedToParent === true) {
          return;
        }
        this.postMessageToParent('kpas-lti.connect');
        window.setTimeout(this.connectToParent, 500);
      },

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
      async updateModule(value){
        if(value == null){
          this.current_module = null
          return;
        }
        this.module_surveys = this.survey_data.filter(e => e.module_id == value.id)
        if(this.module_surveys.length > 0){
          this.view_survey = this.module_surveys[0];
        }
        else {
          this.view_survey = null
        }
        this.current_module = value.id;
        this.updateFinnishCount()
        await this.getModulesStatisticsPerDate();
        this.iframeresize()

      },
      updateSurvey(value){
        this.view_survey = value;
        this.iframeresize()
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
      async getModulesStatisticsPerDate(){
        if(!this.current_module){
          return
        }
        let url = "/modules/" + this.current_module + "/per_date";
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.modules_statistics_per_date = JSON.parse(apiResult.data.result)
        console.log(this.modules_statistics_per_date)
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
              let obj = {"title" : item.title, "count" : item.user_groups[0].count, "position" : item.position}
              this.completed_count_item.push(obj)
            }
            else{
              let obj = {"title" : item.title, "count" : 0, "position" : item.position}
              this.completed_count_item.push(obj)
            }
          }
          else {
            let obj = {"title" : item.title, "count" : item.total_completed, "position" : item.position}
            this.completed_count_item.push(obj)
          }
        }
      }
    },

    async created() {
      const allowedRoles = ['Admin', 'Udirforvalter', 'Udir-forvalter']
      this.allowed = allowedRoles.some(
        (element) => this.settings.custom_canvas_roles.includes(element)
      )

      let self = this;
      const mql = window.matchMedia('(max-width: 500px)');
      mql.onchange = (e) => {
        self.iframeresize();
      }
      window.addEventListener('message', async function(evt) {
        try {
          let msg = JSON.parse(evt.data);
          if(msg.subject == "kpas-lti.ltibgcolor" && msg.bgColor) {
            console.log("Received background color.");
            console.log(msg.bgColor)
            document.body.style.backgroundColor = msg.bgColor;
          } else if(msg.subject == "kpas-lti.ltiparentready") {
            console.log("parent ready")
            self.connectedToParent = true;
            self.getBgColor();
          }
        } catch(e) {
          //This message is not for us.
        }
      }, false);
      self.connectToParent();
      await this.getGroupCategories();
      await this.getStudentCount();
      await this.getModulesStatistics();
      await this.getSurveyData();
      this.ready = true;
    },
  };

</script>

<style>

h3 {
  padding: .5em;
}

.title{
  padding: 1em 0 1em 0;
}

.selector{
  width: fit-content;
  min-width: 50%;
  margin: 1em;
  padding: 0.5;
}

.completed-section,.grouped, .barview, .feedback, .filtering-section, .no-survey, .no-module {
  background-color: white;
  padding: 4em 2em 4em 2em;
  margin: 2em 0 2em 0;
}

</style>
