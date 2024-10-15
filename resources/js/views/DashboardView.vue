@extends('layouts.app')
<template>
  <div ref="ltiView" class="dashboard" v-if="ready && view_module != null && groupMember" >
    <v-select
      label="Label"
      class="selector"
      placeholder="Velg modul"
      v-model="selectedSurvey"
      :options="surveyOptions"
      :disabled="!surveyOptions.length"
      :clearable="false"
      @update:modelValue="handleSurveyChange"
    />

    <h1 class="title">{{ view_module.title_internal }}</h1>
    <h3>Resultater fra {{ current_group_type }}: {{ current_group_name }}</h3>

    <section class="grouped" >
      <grouped-bar-chart
        id="view_module.course_id"
        :likert5ops="likert5ops"
        :data="view_module.questions.slice(0,3)"
      />
    </section>

    <section class="barview" v-if="view_module.questions.length >= 4">
      <div v-for="(question, i) in view_module.questions.slice(3)" :key="i">
        <bar-chart
          :id="'q' + i"
          :svgWidth=600
          :svgHeight=400
          :data="question"
          :likert5ops="likert5ops"
          v-if="question.question_type == 'likert_scale_5pt'"
        />
      </div>
    </section>

    <section class="feedback">
      <div v-for="(question, i) in view_module.questions" :key="i">
        <open-answer
          :questionText="question.text"
          :openAnswers="question.submission_data"
          v-if="question.question_type === 'essay'"
        />
      </div>
    </section>
  </div>

  <div v-else-if="!groupMember && ready">
    <h2>Du må være medlem av en gruppe for å få tilgang til Dashboard.</h2>
  </div>

  <div v-else-if="groupTooSmall">
    <h2>Du er medlem av en gruppe vi ikke kan vise frem data til, dette er på bakgrunn av å holde besvarelser anonyme.</h2>
  </div>

  <div v-else>
      <span class="ml-3">Laster Dashboard. <div class="spinner-border text-success"></div></span>
  </div>
</template>

<script>
// view_module
import api from "../api";
import { extractLabelForSelectedLanguage } from "../mulitlang";
export default {
  name: "DashboardView",
  data() {
    return {
      course_id: null,
      view_module: null,
      survey_data: null,

      selectedSurvey: null,
      surveyOptions: [],

      userGroups: [],
      usersGroups: [],
      categories: null,

      ready: false,
      categoriesLoaded: false,
      groupsLoaded: false,
      
      current_group_name: null,
      current_group_type: "gruppe",
      selectedGroup: null,

      groupMember: false,
      groupTooSmall: false,
      
      multilang: false,
      selectedLang: null,
      connectedToParent: false,
    };
  },
  props: {
    settings: {},
    likert5ops: {},
    coursemodules: []
  },
  computed: {

  },
  methods: {
    handleSurveyChange(newSelectedSurvey) {
      if (newSelectedSurvey != null) {
        this.selectedSurvey = newSelectedSurvey;
        this.view_module = this.survey_data?.find(survey => survey.id === this.selectedSurvey.ID);
      } else {
        this.selectedSurvey = this.surveyOptions[0];
        this.view_module = this.survey_data[0];
      }

      console.log("Selected survey: ", this.selectedSurvey, this.view_module);

      this.iframeresize()
    },
    async updateCurrentGroups() {
      console.log("updateCurrentGroups");
      if(!this.categoriesLoaded) {
        console.log("categories not ready yet.");
        return;
      }

      if(!this.groupsLoaded) {
        console.log("groups not ready yet.");
        return;
      }

      if(this.categories && this.usersGroups) {
        console.log("got categories and usergroups")
        await Promise.all([this.userGroups = this.categorizeGroups(this.usersGroups, this.categories)]);
      }
    },
    categorizeGroups(groups, categories) {
      var result = {};
      var self = this;
      categories.forEach(function(category) {
        var group = groups.find(group => category.id == group.group_category_id && self.course_id == group.course_id);
        if(group) {
          result[category.name] = group;
        }
      });
      return result;
    },
    async getGroupCategories() {
      try {
        const response = await api.get('/group/user', {
          params: {
            cookie: window.cookie,
          }
        });
        this.categories = response.data.result;
        this.categoriesLoaded = true;
        console.log("Categories received.");
      } catch(e){
        console.log("error ", e)
      }
    },
    async isGroupTooSmall(group, type){
      let orgNr = group.description.split(":").at(-1)
      if(orgNr == "999999999"){
        return true;
      }else if(type == "municipality") {
        return false;
      }

      let apiResult;
      if(type == "school"){
        let url = "/school/orgnr/" + orgNr;
        apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
      }else if(type == "kindergarten"){
        let url = "/kindergarten/orgnr/" + orgNr;
        apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
      }

      if(apiResult.data.result.AnsatteTil <= 5){
        return true;
      }

      return false;
    },
    async getSurveyData() {
      try {
        console.log(this.userGroups)
        let groupId = "";
        let groupDescription = "";
        let groupTooSmall = false;
        
        if(Object.hasOwn(this.userGroups, 'Skole')){
          groupId = this.userGroups.Skole.id
          groupDescription = this.userGroups.Skole.description
          this.current_group_name = this.userGroups.Skole.name
          this.current_group_type = !this.current_group_name.toLowerCase().includes("skole") ? "skole" : "gruppe"
          this.groupMember = true
          groupTooSmall = await this.isGroupTooSmall(this.userGroups.Skole, "school")

        } else if(Object.hasOwn(this.userGroups, 'Barnehage')){
          groupId = this.userGroups.Barnehage.id
          groupDescription = this.userGroups.Skole.description
          this.current_group_name = this.userGroups.Barnehage.name
          this.current_group_type = !this.current_group_name.toLowerCase().includes("barnehage") ? "barnehage" : "gruppe"
          this.groupMember = true
          groupTooSmall = await this.isGroupTooSmall(this.userGroups.Barnehage, "kindergarten")
        }else {
          this.groupMember = false;
          return;
        }

        let orgNr = groupDescription.split(":").at(-1)
        if(orgNr == "999999999" && Object.hasOwn(this.userGroups, 'Kommune')){
          groupId = this.userGroups.Kommune.id
          this.current_group_name = this.userGroups.Kommune.name
          this.current_group_type = !this.current_group_name.toLowerCase().includes("kommune") ? "kommune" : "gruppe"
          this.groupMember = true
          groupTooSmall = await this.isGroupTooSmall(this.userGroups.Kommune, "municipality")
        }

        if(groupTooSmall){
          this.groupTooSmall = true;
          return
        }

        let url = "/survey/course/" + this.course_id + "/no_essay?group=" + groupId + "&format=json";
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.survey_data = apiResult.data.result;
        if(this.multilang){
          this.survey_data.forEach(e => {
            e.title_internal = extractLabelForSelectedLanguage(e.title_internal, this.selectedLang)
          })
        }

        this.surveyOptions = this.survey_data.map(survey => {
          const surveyModuleId = survey.module_id;
          let surveyTitle = survey.title_internal;

          const moduleForSurvey = this.coursemodules?.find(moduleItem => moduleItem.id === surveyModuleId);
          if (moduleForSurvey != null) surveyTitle = this.multilang ? extractLabelForSelectedLanguage(moduleForSurvey.name, this.selectedLang) : moduleForSurvey.name;

          const hasCustomTitle = survey?.form_title != null && survey?.form_title != undefined && survey?.form_title != "";
          if (hasCustomTitle) surveyTitle = survey.form_title;

          return {
            Label: surveyTitle,
            ID: survey.id
          }
        });

        this.selectedSurvey = this.surveyOptions[0];
        this.view_module = this.survey_data[0];
      } catch(e) {
        console.log("Could not get survey data.", e);
      }
    },

    getBgColor() {
      this.postMessageToParent('kpas-lti.getBgColor');
    },
    getUsersGroups() {
      console.log("Get users groups.");
      this.postMessageToParent('kpas-lti.getusergroups');
    },
    connectToParent() {
      if(this.connectedToParent === true) {
        return;
      }
      this.postMessageToParent('kpas-lti.connect');
      window.setTimeout(this.connectToParent, 500);
    },
    getCurrentLanguage() {
      this.postMessageToParent('kpas-lti.getcurrentlang');
    },
    postMessageToParent(subject) {
      const message = {
        subject: subject
      };
      window.parent.postMessage(JSON.stringify(message), "*");
    },
    iframeresize() {
      this.$nextTick(function () {
        var h = document.body.clientHeight + 50
        parent.postMessage(
        JSON.stringify({ subject: "lti.frameResize", height: h }),
        "*"
        );
      });
    },
  },
  async created() {
    let self = this;
    self.course_id = self.settings.custom_canvas_course_id
    await self.getGroupCategories()
    self.getCurrentLanguage();
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
          self.getUsersGroups()
        }else if(msg.subject == "kpas-lti.usergroups") {
          self.usersGroups = msg.groups;
          self.groupsLoaded = true;
        }
        else if(msg.subject == "kpas-lti.lang") {
          self.multilang = msg.isMultiLang;
          self.selectedLang = msg.lang;
        }
      } catch(e) {
        //This message is not for us.
      }
    }, false);
    self.connectToParent();
  },
  watch: {
    async groupsLoaded() {
      if(this.groupsLoaded){
        await this.updateCurrentGroups()
        await this.getSurveyData()
        this.ready = true;
        this.iframeresize();
      }

    }

  }

};
</script>

<style>
.feedback {
  background-color: white;
  padding: 5%;
  margin: 5%;
  width: auto ;
}
.dashboard {
  background-color: white;
  align-content: center;
}
.grouped {
  background-color: white;
  width: auto;
  margin: 5%;
  padding: 5%;
}
.barview {
  background-color: white;
  width: auto;
  margin: 5%;
  padding: 5%;
}
.selector {
  width: 40%;
  background-color: white;
}
.title {
  padding-top: .5em;
}
</style>
