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
        :faculties="faculties"
        v-model="faculty"
      />
    <hr/>
      <group-selector
        @update="updateSelectStyles"
        :courseId="courseId"
        :institutionType="institutionType"
        v-model="groups"
      ></group-selector>
    <hr/>
      <div>
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

      <span v-if="isLoading" class="ml-3">Oppdaterer din rolle og gruppetilhørighet. Dette kan ta litt tid. Ikke lukk nettleseren.<div class="spinner-border text-danger"></div></span>
      <p>
      <div v-if="submitSuccess" class='alert alert-success'>Operasjonen var vellykket! Klikk på fanen <i>Forside</i> for å fortsette å jobbe med kompetansepakken.</div>
      </p>
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
      groupsAreSet() {
        return Object.keys(this.groups).length
      },
      isReady() {
        return !this.isLoading && this.groupsAreSet && (this.faculties.length === 0 || this.faculty !== null);
      },
      studentText() {
        return this.participantDescription;
      },
      principalText() {
        return this.leaderDescription;
      }
    },
    data() {
      return {
        everythingIsReady: false,
        information: "Laster inn din rolle...",
        courseId: -1,
        currentGroupsLoaded: false,
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
        faculty: null,
        roleError: '',
        groupError: '',
        submitSuccess: false
      }
    },

    methods: {
      mounted() {
        self.iframeresize();
      },
      updateSelectStyles(){
        var self = this;
        console.log("KPAS-LTI document ready.");
        const properties = {
          width: '100%',
        };
        let s1 = $('.select-county select').select2(properties);
        let s2 = $('.select-community select').select2(properties);
        let s3 = $('.select-school select').select2(properties);
        s1.on('select2:select', function (e) {
          var event = new Event('change');
          e.target.dispatchEvent(event);
        });
        s2.on('select2:select', function (e) {
          var event = new Event('change');
          e.target.dispatchEvent(event);
        });
        s3.on('select2:select', function (e) {
          var event = new Event('change');
          e.target.dispatchEvent(event);
        });
        self.iframeresize();
        self.getUsersGroups();
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
            this.roleError = errorMsg;
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
        return "Du er registrert som: " + this.leaderDescription;
      },
      getParticipantInformation() {
        return "Du er registrert som: " + this.participantDescription;
      },
      getUsersGroups() {
        console.log("Get users groups.");
        const getusergroupsMessage = {
          subject: 'kpas-lti.getusergroups'
        }
        window.parent.postMessage(JSON.stringify(getusergroupsMessage), "*");
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
        if (this.groupsAreSet) {
          const params = Object.assign({}, 
            this.groups, {
            cookie: window.cookie,
            role: this.wantToBePrincipal ? process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE : process.env.MIX_CANVAS_STUDENT_ROLE_TYPE,
            faculty: this.faculty,
            currentGroups: this.currentGroups,
            courseId: this.courseId
          });
          try {
            await api.post('/group/user/bulk', params);
            this.clearError("groupError");
            this.iframeresize();
          } catch(e) {
            this.reportError("groupError", "Kunne ikke melde deg inn i gruppen(e).");
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
            role: this.wantToBePrincipal ? process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE : process.env.MIX_CANVAS_STUDENT_ROLE_TYPE,
            cookie: window.cookie,
          });
          this.clearError("roleError");
          this.iframeresize();
        } catch(e) {
          this.reportError("roleError", "Kunne ikke oppdatere rollen.");
          this.iframeresize();
        }
      },
      async enroll() {
        console.log("WTBP:" + this.wantToBePrincipal);
        if (this.isReady) {
          this.isLoading = true;
          this.submitSuccess = false;
          try {
            await this.enrollUser();
            await this.addUserGroups();
            this.isPrincipal = this.wantToBePrincipal;
            if(this.isPrincipal) {
              this.information = this.getPrincipalInformation();
            } else {
              this.information = this.getParticipantInformation();
            }
            this.submitSuccess = true; 
          } catch (e) {
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
          this.isPrincipal = result.data.result.find(enrollment => enrollment.role === process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE) != null;
          if(this.isPrincipal) {
            this.information = this.getPrincipalInformation();
          } else {
            this.information = this.getParticipantInformation();
          }
          this.wantToBePrincipal = this.isPrincipal;
          this.clearError("roleError");
        } catch(e)
        {
          this.reportError("roleError", "Kunne ikke hente rolle.");
        }
      },
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
      }
    },
    async created() {
      console.log("LTI listening for messages from parent.");
      var self = this;
      const getBgColorMessage = {
        subject: 'kpas-lti.getBgColor'
      }
      window.parent.postMessage(JSON.stringify(getBgColorMessage), "*");

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
              console.log("KPAS-LTI received LTP parent ready.");
              document.body.style.backgroundColor = msg.bgColor;
          } else if(msg.subject == "kpas-lti.ltiparentready") {
              self.getUsersGroups();
          }
        } catch(e) {
          console.log("kpas-lti: exception parsing message " + e);
          console.log("When processing " + evt.data);
        }
      }, false);

      console.log("Hent kategorier...");
      await Promise.all([self.getGroups(), self.getFaculties(), self.getRole(), self.getInstitution()]);
      this.iframeresize();
      console.log("KPAS ready to display.");
      self.everythingIsReady = true;
    },
  }
</script>
