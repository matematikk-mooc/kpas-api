<template>
  <div v-if="everythingIsReady">
      <h2>Din rolle</h2>
      <current-role
        :isPrincipal="isPrincipal"
        :institutionType="institutionType"
        :information="information"
      ></current-role>
      <div v-if="roleError"
        class="alert alert-danger">{{roleError}}
      </div>
      <hr/>
      <h2>Dine grupper</h2>
      <current-group
        :groups="currentGroups"
        :groupsLoaded="currentGroupsLoaded"
      ></current-group>
      <div v-if="groupError"
        class="alert alert-danger">{{groupError}}
      </div>
      <hr/>
      <h2>Velg rolle</h2>
      <role-selector
        :isPrincipal="isPrincipal"
        :institutionType="institutionType"
        :leaderDescription="leaderDescription"
        :participantDescription="participantDescription"
        v-model="wantToBePrincipal"
      ></role-selector>
    <hr/>
      <h2>Velg grupper</h2>
      <faculty-selector
        @updateFaculty="updateFaculty"
        :faculties="faculties"
        v-model="faculty"
      />
    <hr/>
      <group-selector
        @updateGroups="updateGroups"
        :courseId="courseId"
        :institutionType="institutionType"
        v-model="groups"
      ></group-selector>
    <hr/>
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


      <div v-if="isLoading" class="alert alert-warning kpasAlert">Oppdaterer din rolle og gruppetilhørighet. Dette kan ta litt tid. Ikke lukk nettleseren.<div class="spinner-border text-danger"></div></div>
      <div v-if="enrollResult == ENROLL_FAILED" class='alert alert-danger kpasAlert'>Kunne ikke oppdatere rollen din. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.</div>
      <div v-if="getRoleResult == ENROLL_GET_FAILED" class='alert alert-danger kpasAlert'>Du er ikke registrert med noen rolle i kompetansepakken og kan derfor ikke endre den eller melde deg inn i noen grupper.</div>
      <div v-if="groupResult == ADDTO_GROUPS_FAILED" class='alert alert-danger kpasAlert'>Kunne ikke melde deg inn i gruppene. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.</div>
      <div v-if="enrollResult == ENROLLED && !settings.deep && groupResult == ADDED_TO_GROUPS" class='alert alert-success kpasAlert'>Oppdateringen var vellykket! Klikk på fanen <i>Forside</i> for å fortsette å jobbe med kompetansepakken.</div>
      <div v-if="enrollResult == ENROLLED && settings.deep && groupResult == ADDED_TO_GROUPS" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</div>

  </div>
  <div v-else>
      <span class="ml-3">Laster rolle og gruppeverktøyet. <div class="spinner-border text-success"></div></span>
  </div>
</template>

<script>
  import api from '../api';
  import RoleSelector from "../components/RoleSelector";
  import CurrentRole from "../components/CurrentRole";
  import GroupSelector from "../components/GroupSelector";
  import CurrentGroup from "../components/CurrentGroup";
  import FacultySelector from "../components/FacultySelector";

  export default {
    name: "GroupEnrollView",
    components: {
      RoleSelector,
      CurrentRole,
      GroupSelector,
      CurrentGroup,
      FacultySelector,
    },
    computed: {
      studentText() {
        return this.participantDescription;
      },
      principalText() {
        return this.leaderDescription;
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
        wantToBePrincipal: false,
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
      }
    },

    methods: {
      mounted() {
        self.iframeresize();
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
          var h = document.body.clientHeight + 100;
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
//            this.reportError("groupError", "Kunne ikke melde deg inn i gruppen(e).");
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
            window.parent.postMessage(JSON.stringify(updateMessage), "*");
          }
        }
      },
      async getRole() {
        try {
          const result = await api.get('/enrollment/', {
            params: { cookie: window.cookie }
          });
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
      await Promise.all([self.getGroups(), self.getFaculties(), self.getRole(), self.getInstitution(), self.getSettings()]);
      this.iframeresize();
      console.log("KPAS ready to display.");
      self.everythingIsReady = true;
    }
  }
</script>

<style>
  .update-button {
    padding-bottom: 2em;
  }
</style>