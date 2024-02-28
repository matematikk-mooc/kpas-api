<template>
  <div class="group-tool" v-if="everythingIsReady">
      <h2>Velg rolle</h2>
      <Message v-if="roleError" type="error"><span>{{roleError}}</span></Message>
      <role-selector
        :isPrincipal="isPrincipal"
        :institutionType="institutionType"
        :leaderDescription="leaderDescription"
        :participantDescription="participantDescription"
        v-model="wantToBePrincipal"
      ></role-selector>
      <h2>Velg grupper</h2>
      <Message type="error" v-if="wantToBePrincipal && institutionType">{{principalWarning}}
      </Message>
      <div v-if="groupError"
        class="alert alert-danger">{{groupError}}
      </div>
      <div v-if="preKommunereform2024">
        <Message type="error">
          <span>
          Per 01.01.2024 ble <a style="font-size: 14px;" href="https://www.regjeringen.no/no/tema/kommuner-og-regioner/kommunestruktur/nye-kommune-og-fylkesnummer-fra-1.-januar-2024/id2924701/" target="_blank">Kommunereformen 2024</a> innført. Dersom du jobber i ett fylke eller kommune som er berørt av kommunereformen 2024 må du velge nye grupper. Du finner oppdaterte kommuner i gruppevalget nedenfor.
          Merk: Vestfold og Telemark, Viken og Troms og Finnmark er ikke gyldige valg lengre, disse er fjernet fra listen.
          Når du bytter gruppe blir historikken din flyttet til ny gruppe.
        </span>
        </Message>
      </div>
      <faculty-selector
        @updateFaculty="updateFaculty"
        :faculties="faculties"
        :currentFaculty="currentGroups['Faggruppe nasjonalt']? currentGroups['Faggruppe nasjonalt'].name : ''"
        v-model="faculty"
      />
      <group-selector
        @updateGroups="updateGroups"
        :courseId="courseId"
        :institutionType="institutionType"
        :currentGroups="currentGroups"
        v-model="groups"
      ></group-selector>
      <div class="update-button">
        <button
          class="btn"
          :disabled="!isReady"
          :class="{
            'btn-primary': isReady,
            'btn-secondary disabled': !isReady,
          }"
          @click="enroll"
        >
          Oppdater
        </button>
      </div>


      <Message v-if="isLoading" type="warn"><span>Oppdaterer din rolle og gruppetilhørighet. Dette kan ta litt tid. Ikke lukk nettleseren.<div class="spinner-border text-danger"></div></span></Message>
      <Message v-if="enrollResult == ENROLL_FAILED" type="error"><span>Kunne ikke oppdatere rollen din. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.</span></Message>
      <Message v-if="getRoleResult == ENROLL_GET_FAILED" type="error"><span>Du er ikke registrert med noen rolle i kompetansepakken og kan derfor ikke endre den eller melde deg inn i noen grupper.</span></Message>
      <Message v-if="groupResult == ADDTO_GROUPS_FAILED" type="error"><span>Kunne ikke melde deg inn i gruppene. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.</span></Message>
      <Message v-if="enrollResult == ENROLLED && groupResult == ADDED_TO_GROUPS" type="success"><span>Oppdateringen var vellykket!</span></Message>

  </div>
  <div v-else>
      <span class="ml-3">Laster rolle og gruppeverktøyet. <div class="spinner-border text-success"></div></span>
  </div>
</template>

<script>
  import api from '../api';
  import RoleSelector from "../components/RoleSelector";
  import GroupSelector from "../components/GroupSelector";
  import FacultySelector from "../components/FacultySelector";
  import Message from "../components/Message";

  export default {
    name: "GroupEnrollView",
    components: {
      RoleSelector,
      GroupSelector,
      FacultySelector,
      Message
    },
    computed: {
      studentText() {
        return this.participantDescription;
      },
      principalText() {
        return this.leaderDescription;
      },
      principalWarning() {
        if(this.institutionType == "school") {
          return "NB! Dersom du er skoleeier kan du velge tilhørighet til Annet/Annen fylke/kommune/skole.";
        } else if(this.institutionType == "kindergarten") {
          return "NB! Dersom du er barnehageeier kan du velge tilhørighet til Annet/Annen fylke/kommune/barnehage.";
        }
        return "";
      },
    },
    data() {
      return {
        everythingIsReady: false,
        information: "Laster inn din rolle...",
        courseId: -1,
        currentGroupsLoaded: false,
        connectedToParent: false,
        groupsLoaded: false,
        categoriesLoaded: false,
        isPrincipal: false,
        wantToBePrincipal: undefined,
        institutionType: null,
        leaderDescription: null,
        participantDescription: null,
        groups: [],
        currentGroups: null,
        faculties: [],
        isLoading: false,
        isReady: false,
        faculty: null,
        roleError: '',
        groupError: '',
        enrollResult: 0,
        roleIsSet: true,
        groupResult: 0,
        getRoleResult: 0,
        preKommunereform2024: false
      }
    },

    methods: {
      preKommunereform2024Check(){
        if(this.currentGroups == null || this.currentGroups.Fylke == undefined || this.currentGroups.Fylke.description == undefined){
          return;
        }
        const patternNumbers = [30, 38, 54]; // 30 = Viken, 38 = Vestfold og Telemark, 54 = Troms og Finnmark
        const pattern = `county:(${patternNumbers.join('|')})`;
        const regex = new RegExp(pattern, 'g');
        if(this.currentGroups.Fylke.description.match(regex)){
          console.log("Fylke er berørt av kommunereformen 2024");
          this.preKommunereform2024 = true;
        }
      },
      groupsAreSet() {
        var noOfGroups = Object.keys(this.groups).length;
        return this.institutionType ?  noOfGroups === 3 : noOfGroups === 2;
      },
      updateIsReady() {
        this.isReady = !this.isLoading &&
                        this.groupsAreSet() &&
                        this.roleIsSet &&
                        this.getRoleResult != this.ENROLL_GET_FAILED &&
                        (this.faculties.length === 0 || this.faculty !== null);
      },
      updateFaculty() {
        this.updateIsReady();
      },
      updateGroups(selectedGroups) {
        this.groups = selectedGroups;
        this.updateIsReady();
      },
      clearError(errorType) {
          if(errorType == "roleError") {
            this.roleError = "";
          } else if(errorType == "groupError") {
            this.groupError = "";
          }
          this.iframeresize();
      },
      reportError(errorType,e) {
          var errorMsg = e + " Prøv igjen senere og ta kontakt med kompetansesupport@udir.no dersom feilen vedvarer.";
          if(errorType == "roleError") {
            this.roleIsSet = false;
            this.roleError = errorMsg;
            this.role
          } else if(errorType == "groupError") {
            this.groupError = errorMsg;
          }
          this.iframeresize();
      },
      iframeresize() {
        this.$nextTick(function () {
          var h = document.body.clientHeight + 250;
          parent.postMessage(JSON.stringify({ subject:"lti.frameResize", height: h }), "*");
        });
      },
      getRoleText(isPrincipal) {
        return isPrincipal ? this.leaderDescription : this.participantDescription;
      },
      getPrincipalInformation() {
        return this.leaderDescription;
      },
      getParticipantInformation() {
        return this.participantDescription;
      },
      postMessageToParent(subject) {
        const message = {
          subject: subject
        };
        window.parent.postMessage(JSON.stringify(message), "*");
      },
      getUsersGroups() {
        console.log("Get users groups.");
        this.postMessageToParent('kpas-lti.getusergroups');
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
          this.currentGroups = this.categorizeGroups(this.usersGroups, this.categories);
          this.iframeresize();
        }
        this.currentGroupsLoaded = true;
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
      async addUserGroups() {
        if (this.groupsAreSet()) {
          const params = Object.assign({},
            this.groups, {
            cookie: window.cookie,
            role: this.wantToBePrincipal ? import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE : import.meta.env.VITE_CANVAS_STUDENT_ROLE_TYPE,
            faculty: this.faculty,
            currentGroups: this.currentGroups,
            courseId: this.courseId
          });
          try {
            const result = await api.post('/group/user/bulk', params);
            this.clearError("groupError");
            this.iframeresize();
            this.groupResult = this.ADDED_TO_GROUPS;
          } catch(e) {
            this.groupResult = this.ADDTO_GROUPS_FAILED;
            this.iframeresize();
          }
        }
      },
      async getInstitution() {
        try {
          const result = await api.post('/institution', {
            cookie: window.cookie,
          });
          this.institutionType = result.data.result.institutionType;
          this.leaderDescription= result.data.result.institutionLeaderDescription;
          this.participantDescription = result.data.result.institutionParticipantDescription;
          this.clearError();
          this.iframeresize();
        } catch (e) {
          this.reportError("Kunne ikke hente institution type.");
          this.iframeresize();
        }
      },
      async enrollUser() {
        try {
          await api.post('/enrollment', {
            role: this.wantToBePrincipal ? import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE : import.meta.env.VITE_CANVAS_STUDENT_ROLE_TYPE,
            cookie: window.cookie,
          });
          this.clearError("roleError");
          this.iframeresize();
          this.enrollResult = this.ENROLLED;
        } catch(e) {
          this.enrollResult = this.ENROLL_FAILED;
//          this.reportError("roleError", "Kunne ikke oppdatere rollen.");
          this.iframeresize();
        }
      },
      async enroll() {
        if(this.isLoading) {
          return;
        }
        console.log("WTBP:" + this.wantToBePrincipal);
        if (this.isReady) {
          this.isLoading = true;
          this.enrollResult = 0;
          this.groupResult = 0;
          try {
            await this.enrollUser();
            await this.addUserGroups();
            this.isPrincipal = this.wantToBePrincipal;
            if(this.isPrincipal) {
              this.information = this.getPrincipalInformation();
            } else {
              this.information = this.getParticipantInformation();
            }
          } catch (e) {
            console.log("Failed to update user information.");
          } finally {
            this.isLoading = false;
            this.getUsersGroups();
            const updateMessage = {
              subject: 'kpas-lti.update'
            }
            this.preKommunereform2024 = false;
            window.parent.postMessage(JSON.stringify(updateMessage), "*");
          }
        }
      },
      async getRole() {
        try {
          const result = await api.get('/enrollment/', {
            params: { cookie: window.cookie }
          });

          if(result.data.result.length === 0) {
            this.getRoleResult = this.ENROLL_GET_FAILED;
            return;
          }

          this.isPrincipal = result.data.result.find(enrollment => enrollment.role === import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE) != null;
          if(this.isPrincipal) {
            this.information = this.getPrincipalInformation();
          } else {
            this.information = this.getParticipantInformation();
          }
          this.wantToBePrincipal = this.isPrincipal;
          this.clearError("roleError");
        } catch(e)
        {
          this.getRoleResult = this.ENROLL_GET_FAILED;
          this.wantToBePrincipal = undefined;
          this.reportError("roleError", "Kunne ikke hente rolle.");
        }
      },
      //Note that this method gets the group categories.
      async getGroups() {
        try {
          const response = await api.get('/group/user', {
            params: {
              cookie: window.cookie,
            }
          });
          this.categories = response.data.result;
          this.courseId = this.categories[0].course_id;
          this.categoriesLoaded = true;

          console.log("Categories received.");
          this.updateCurrentGroups();
          this.clearError("groupError");
        } catch(e)
        {
          this.reportError("groupError", "Kunne ikke hente grupper.");
        }
      },

      async getFaculties() {
        const response = await api.get('/faculties', {
          params: {
            cookie: window.cookie,
          }
        });
        this.faculties = response.data.result;
      },
      async getSettings() {
        const response = await api.get('/settings', {
          params: {
            cookie: window.cookie,
          }
        });
        this.settings = response.data.result;
        console.log(this.settings);
      }

    },
    updated() {
      this.iframeresize();
    },
    async created() {
      this.ENROLLED = 1;
      this.ADDED_TO_GROUPS = 2;
      this.ENROLL_FAILED = 3;
      this.ADDTO_GROUPS_FAILED = 4;
      this.ENROLL_GET_FAILED = 5;

      console.log("LTI listening for messages from parent.");
      var self = this;

      window.addEventListener('message', function(evt) {
        try {
          var msg = JSON.parse(evt.data);
          if(msg.subject == "kpas-lti.usergroups") {
            console.log("Storing groups.");
            self.usersGroups = msg.groups;
            self.groupsLoaded = true;
            self.updateCurrentGroups();
          }
          else if(msg.subject == "kpas-lti.ltibgcolor" && msg.bgColor) {
            console.log("Received background color.");
            document.body.style.backgroundColor = msg.bgColor;
          } else if(msg.subject == "kpas-lti.ltiparentready") {
            self.connectedToParent = true;
            self.getBgColor();
            self.getUsersGroups();
          }
        } catch(e) {
          //This message is not for us.
        }
      }, false);

      self.connectToParent();

      console.log("Hent kategorier...");
      await Promise.all([self.getGroups(), self.getFaculties(), self.getRole(), self.getInstitution(), self.getSettings()]).then(() => {
        console.log("All promises resolved.");
        self.updateIsReady();
        self.iframeresize();
        self.preKommunereform2024Check();
        self.everythingIsReady = true;
      });
    }
  }
</script>

<style scoped>


  h2{
    margin-top: .5em;
    margin-bottom: .5em;
    font-weight: bold;
    font-size: 22px;
  }

  .btn-primary {
    cursor: pointer;
    position: relative;
    background: #303030;
    color: white;
    border: none;
    border-radius: 0.1875rem;
    font-weight: 700;
    line-height: 1;
    display: flex;
    align-items: center;
    bottom: -0.05rem;
    padding: 0.5rem 1.375rem 0.5rem 1.375rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
  }
  .btn-primary:hover {
    background: #00468e;
    color: white;
  }

  .btn-secondary {
    cursor: pointer;
    position: relative;
    background: #737373;
    color: white;
    border: none;
    border-radius: 0.1875rem;
    font-weight: 700;
    line-height: 1;
    display: flex;
    align-items: center;
    bottom: -0.05rem;
    padding: 0.5rem 1.375rem 0.5rem 1.375rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
  }

  .group-tool {
    padding: 1rem 1rem 3rem 1rem;
    gap: .5rem;
    display: flex;
    flex-direction: column;
  }

</style>
