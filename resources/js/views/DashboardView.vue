@extends('layouts.app')
<template>
  <div class="dashboard" v-if="ready && view_module != null && groupMember" >
    <v-select
      class="selector"
      :disabled="!modules.length"
      :options="modules"
      placeholder="Velg modul"
      @update:modelValue="updateModule"
    >
    </v-select>

  
    <h1 class="title">{{view_module.title_internal}}</h1>
    <h3>Resultater fra gruppe: {{ current_group_name }}</h3> 

    <section class="grouped" >
      <grouped-bar-chart id="view_module.course_id" :data="view_module.questions.slice(0,3)" :likert5ops="this.likert5ops"></grouped-bar-chart>
    </section>

    <section class="barview" v-if="view_module.questions.length > 4">
      <div v-for="(question, i) in view_module.questions.slice(3)" :key="i">
        <bar-chart v-if="question.question_type == 'likert_scale_5pt'" :id="'q' + i" :data="question" :svgWidth=600 :svgHeight=400></bar-chart>
      </div>
    </section>

    <section class="feedback">
      <div v-for="(question, i) in view_module.questions" :key="i">
        <open-answer v-if="question.question_type === 'essay'" :openAnswers="question.submission_data" :questionText="question.text"></open-answer>
      </div>
    </section>
  </div>
  <div v-else-if="!groupMember && ready"> 
    <h2>Du må være medlem av en gruppe for å få tilgang til Dashboard</h2>
  </div>
  <div v-else>
      <span class="ml-3">Laster Dashboard. <div class="spinner-border text-success"></div></span>
  </div>

</template>

<script>
import api from "../api";
export default {
  name: "DashboardView",
  data() {
    return {
      likert5ops: this.likert5ops, 
      connectedToParent: false,
      selectedGroup: null,
      ready: false,
      view_module: null,
      survey_data : null,
      modules: [],
      userGroups: [],
      usersGroups: [], 
      categories: null, 
      categoriesLoaded: false,
      groupsLoaded: false,
      course_id: null, 
      current_group_name: null,
      groupMember: false
    };
  },
  props: {
    settings: {},
    likert5ops: {}
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
    postMessageToParent(subject) {
      const message = {
        subject: subject
      };
      window.parent.postMessage(JSON.stringify(message), "*");
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
  
    updateModule(value){
      if(value == null){
        this.view_module = this.survey_data[0];
        return;
      }
      let index = this.modules.indexOf(value)
      this.view_module =  this.survey_data[index]
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
    async getSurveyData() {
      try {
        let groupId = "";
        if(this.userGroups.Skole){
          groupId = this.userGroups.Skole.id
          this.current_group_name = this.userGroups.Skole.name
          this.groupMember = true

        } else if(this.userGroups.Barnehage){
          groupId = this.userGroups.Skole.id
          this.current_group_name = this.userGroups.Barnehage.name
          this.groupMember = true

        }else {
          this.groupMember = false; 
          return;          
        }

        let url = "/survey/course/" + this.course_id + "?group=" + groupId + "&format=json";
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.survey_data = apiResult.data.result;
        this.modules = this.survey_data.map(survey => survey.title_internal)
        this.updateModule(this.modules[0]);
      } catch(e)
      {
        console.log("Could not get survey data.", e);
      }
    },
  },
  async created() {
    let self = this;
    self.course_id = self.settings.custom_canvas_course_id
    await self.getGroupCategories()
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
          self.groupsLoaded = true;
          self.usersGroups = msg.groups;
          await self.updateCurrentGroups()
          await self.getSurveyData()
          self.ready = true;
        }
      } catch(e) {
        //This message is not for us.
      }
    }, false);
    self.connectToParent();  
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
  background-color: #eaeaea;
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