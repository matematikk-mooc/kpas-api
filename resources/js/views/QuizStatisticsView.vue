@extends('layouts.app')
<template>
  <div class="dashboard" v-if="ready && view_module != null" >
    <!-- HAVE NOT REMOVED THIS YET DUE TO USEFUL DURING TESTING    
    <h1>Quiz statistikk</h1>
    <input v-on:keyup.enter="getQuizData" type="number" placeholder="Kompetansepakke id" v-model="course_id"/>
    <button @click="getQuizData">Finn kompetansepakkens quizzer</button> -->
    
    <v-select
      class="selector"
      :disabled="!modules.length"
      :options="modules"
      placeholder="Velg modul"
      @update:modelValue="updateModule"
    >
    </v-select>

    <v-select
      class="selector"
      :disabled="!userGroups.length"
      :options="userGroups"
      label="name"
      placeholder="Velg gruppe"
      @update:modelValue="updateGroup"
    >
    </v-select>
  
    <h1 class="title">{{view_module.title}}</h1>
    <p>Antall besvarelser: {{view_module.submission_statistics[0].unique_count}}</p> 

    <section class="barview">
      <div v-for="(question, i) in view_module.question_statistics" :key="i">
        <bar-chart v-if="question.question_type !== 'essay_question'" :id="'q' + i" :data="question" :svgWidth="600" :svgHeight="400"></bar-chart>
      </div>
    </section>

    <section class="feedback">
      <div v-for="(question, i) in view_module.question_statistics" :key="i">
        <open-answer v-if="question.question_type === 'essay_question'" :openAnswers="question.open_responses" :questionText="question.question_text" :responseCount="question.responses"></open-answer>
      </div>
    </section>
  </div>
  <div v-else>
      <span class="ml-3">Laster Dashboard. <div class="spinner-border text-success"></div></span>
  </div>
</template>

<script>
import api from "../api";

export default {
  name: "QuizStatisticsView",
  data() {
    return {
      connectedToParent: false,
      selectedGroup: null,
      ready: false,
      view_module: null,
      quiz_data : null,
      modules: [],
      groups: ["gruppe 1", "gruppe 2", "gruppe 3"],
      userGroups: [],
      categories: null, 
      categoriesLoaded: false,
      groupsLoaded: false
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
    getBgColor() {
      this.postMessageToParent('kpas-lti.getBgColor');
    },
    postMessageToParent(subject) {
      const message = {
        subject: subject
      };
      window.parent.postMessage(JSON.stringify(message), "*");
    },
    connectToParent() {
      if(this.connectedToParent === true) {
        return;
      }
      this.postMessageToParent('kpas-lti.connect');
      window.setTimeout(this.connectToParent, 500);
    },
    getUsersGroups() {
        console.log("Get users groups.");
        this.postMessageToParent('kpas-lti.getusergroups');
      },
    updateModule(value){
      console.log(value)
      let index = this.modules.indexOf(value)
      console.log(index)
      this.view_module =  this.quiz_data[index]
    },
    updateGroup(value){
      console.log("group", value);
      this.selectedGroup = value; 
      console.log(this.selectedGroup)
    },
    updateCurrentGroups() {
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
          this.userGroups = this.categorizeGroups(this.usersGroups, this.categories);
          this.iframeresize();
        }
      },
      categorizeGroups(groups, categories) {
        var result = {};
        var self = this;
        groups.forEach(function(group) {
          var category = categories.find(category => category.id == group.group_category_id && self.courseId == group.course_id);
          if(category) {
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
          this.updateCurrentGroups();
          //this.clearError("groupError");
        } catch(e){
          console.log("error ", e)
          //this.reportError("groupError", "Kunne ikke hente grupper.");
        }
      },
    async getQuizData(groups_comma_separated) {
      try {
        let courseId = this.course_id
        console.log("GCS ", groups_comma_separated)
        let url = "/course/" + courseId + "/quizzes?groups=" + groups_comma_separated + "&format=json";
        console.log("url ", url)
        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.quiz_data = JSON.parse(apiResult.data.result);
        console.log("qdata length ", this.quiz_data.length)
        console.log("quizdata ", this.quiz_data)
        this.modules = this.quiz_data.map(quiz => quiz.title)
        console.log(this.modules)
        this.updateModule(this.modules[0]);
      } catch(e)
      {
        console.log("Could not get quiz data.", e);
      }
      this.iframeresize()
    },
  },
  async created() {
    let self = this;
    self.course_id = self.settings.custom_canvas_course_id
    console.log(self.settings)
    const mql = window.matchMedia('(max-width: 400px)');
    mql.onchange = (e) => { 
      self.iframeresize();
    }

    window.addEventListener('message', function(evt) {
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
          self.updateCurrentGroups();
        }
      } catch(e) {
        //This message is not for us.
      }
    }, false);

    self.connectToParent();
    await Promise.all([self.getGroupCategories(), self.getQuizData()])
    this.ready = true
    console.log("UG ", self.userGroups)
  }
};
</script> 

<style>
.feedback {
  background-color: white;
  padding: 5%;
  margin: 5%;
  width: 80%;
}

.dashboard {
  background-color: #eaeaea;
  align-content: center;
}

.barview {
  background-color: white;
  width: 80%;
  margin: 5%;
  padding: 5%;
}

.selector {
  width: 40%;
}

.title {
  padding-top: .5em;
}

</style>