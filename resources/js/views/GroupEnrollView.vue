<template>
  <div class="group-tool">
    <div v-if="everythingIsReady">
      <div>
        <h2>1. Velg rolle</h2>
       
        <role-selector
          :institutionType="institutionType"
          :leaderDescription="leaderDescription"
          :participantDescription="participantDescription"

          :isLeader="isLeader"
          @updateIsLeader="updateIsLeader"
        />

        <Message v-if="roleError" type="error"><span>{{ roleError }}</span></Message>
      </div>

      <div>
        <div v-if="faculties?.length">
          <h2>2. Velg fakultet</h2>
          <div v-if="groupError" class="alert alert-danger">{{ groupError }}</div>

          <faculty-selector :faculties="faculties" :selectedFaculty="selectedFaculty" @updateSelectedFaculty="updateSelectedFaculty" />

          <Message v-if="groupError" type="error"><span>{{ groupError }}</span></Message>
        </div>

        <div v-if="!faculties?.length || isFacultySelected">
          <h2>{{ faculties?.length ? "3" : "2" }}. Velg grupper</h2>

          <div v-if="preKommunereform2024">
            <Message type="warn">
              <span>
                Per 01.01.2024 ble <a style="font-size: 14px;" href="https://www.regjeringen.no/no/tema/kommuner-og-regioner/kommunestruktur/nye-kommune-og-fylkesnummer-fra-1.-januar-2024/id2924701/" target="_blank">Kommunereformen 2024</a> innført. Dersom du jobber i ett fylke eller kommune som er berørt av kommunereformen 2024 må du velge nye grupper. Du finner oppdaterte kommuner i gruppevalget nedenfor.
                Merk: Vestfold og Telemark, Viken og Troms og Finnmark er ikke gyldige valg lengre, disse er fjernet fra listen.
                Når du bytter gruppe blir historikken din flyttet til ny gruppe.
              </span>
            </Message>
          </div>

          <Message type="info" v-if="isLeader && institutionType != null">{{ principalWarning }}</Message>

          <group-selector
            :courseId="courseId"
            :institutionType="institutionType"

            :selectedGroups="selectedGroups"
            @updateSelectedGroups="updateSelectedGroupsState"
          />

          <Message v-if="groupResult == ADDTO_GROUPS_FAILED" type="error"><span>Kunne ikke melde deg inn i gruppene. Prøv igjen senere eller ta kontakt med kompetansesupport@udir.no for å få hjelp.</span></Message>
        </div>
      </div>

      <div>
        <div class="update-button">
          <Message v-if="isLoading" type="warn"><span>Oppdaterer din rolle og gruppetilhørighet. Dette kan ta litt tid. Ikke lukk nettleseren.<div class="spinner-border text-danger"></div></span></Message>
          <Message v-if="getRoleResult == ENROLL_GET_FAILED" type="error"><span>Du er ikke registrert med noen rolle i kompetansepakken og kan derfor ikke endre den eller melde deg inn i noen grupper.</span></Message>
          <Message v-if="enrollResult == ENROLLED && groupResult == ADDED_TO_GROUPS" type="success"><span>Oppdateringen var vellykket!</span></Message>

          <button class="btn btn-primary" @click="enroll" v-if="isUpdateReady">
            Oppdater
          </button>
        </div>
      </div>
    </div>

    <div v-else>
      <span class="ml-3">Laster rolle og gruppeverktøyet. <div class="spinner-border text-success"></div></span>
    </div>
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
      isUpdateReady() {
        if (this.isLoading) return false;
        else if (this.getRoleResult == this.ENROLL_GET_FAILED) return false;
        else if (this.isLeader != true && this.isLeader != false) return false;
        else if (this.faculties?.length && !this.isFacultySelected) return false;
        else if (!this.isGroupSelected) return false;

        return true;
      },
      isFacultySelected() {
        return this.selectedFaculty != null;
      },
      isGroupSelected() {
        if (this.institutionType == "school") return this.selectedGroups?.Fylke != null && this.selectedGroups?.Kommune != null && this.selectedGroups?.Skole != null;
        else if (this.institutionType == "kindergarten") return this.selectedGroups?.Fylke != null && this.selectedGroups?.Kommune != null && this.selectedGroups?.Barnehage != null;
        return this.selectedGroups?.Fylke != null && this.selectedGroups?.Kommune != null;
      },
      studentText() {
        return this.participantDescription;
      },
      principalText() {
        return this.leaderDescription;
      },
      principalWarning() {
        if(this.institutionType == "school") {
          return "NB! Dersom du er skoleeier kan du velge \"Annen\" for skole.";
        } else if(this.institutionType == "kindergarten") {
          return "NB! Dersom du er barnehageeier kan du velge \"Annen\" for barnehage.";
        }
        return "";
      },
    },
    data() {
      return {
        courseId: -1,
        roleIsSet: true,
        connectedToParent: false,
        preKommunereform2024: false,
        faculties: [],
        
        isLeader: false,
        selectedFaculty: null,
        selectedGroups: null,
        initGroups: null,

        institutionType: null,
        leaderDescription: null,
        participantDescription: null,
        information: "Laster inn din rolle...",

        categoriesLoaded: false,
        groupsLoaded: false,
        enrollResult: 0,
        groupResult: 0,
        getRoleResult: 0,

        isLoading: false,
        everythingIsReady: false,

        roleError: '',
        groupError: '',
      }
    },

    methods: {
      updateIsLeader(newIsLeader) {
        this.isLeader = newIsLeader == true;
      },
      updateSelectedFaculty(newFacultyName) {
        this.selectedFaculty = newFacultyName;
      },
      updateSelectedGroupsState(newGroups) {
        this.selectedGroups = newGroups;
      },

      preKommunereform2024Check(){
        const groupHasFylke = this.selectedGroups != null && this.selectedGroups.Fylke != undefined && this.selectedGroups.Fylke.description == undefined;
        if(!groupHasFylke) return;

        const patternNumbers = [30, 38, 54]; // 30 = Viken, 38 = Vestfold og Telemark, 54 = Troms og Finnmark
        const pattern = `county:(${patternNumbers.join('|')})`;
        const regex = new RegExp(pattern, 'g');
        
        if(this.selectedGroups.Fylke.description.match(regex)){
          console.log("Fylke er berørt av kommunereformen 2024");
          this.preKommunereform2024 = true;
        }
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
        if(this.connectedToParent === true) return;
        this.postMessageToParent('kpas-lti.connect');
        window.setTimeout(this.connectToParent, 500);
      },
      updateSelectedGroups() {
        console.log("updateSelectedGroups");
        if(!this.categoriesLoaded) {
          console.log("categories not ready yet.");
          return;
        }

        if(!this.groupsLoaded) {
          console.log("groups not ready yet.");
          return;
        }

        if(this.categories && this.usersGroups) {
          this.selectedGroups = this.categorizeGroups(this.usersGroups, this.categories);
          this.initGroups = this.categorizeGroups(this.usersGroups, this.categories);

          if (this.selectedGroups['Faggruppe nasjonalt']) {
            this.selectedFaculty = this.selectedGroups['Faggruppe nasjonalt']?.name;
          } else if (this.selectedGroups['Faggruppe fylke']) {
            this.selectedFaculty = this.selectedGroups['Faggruppe fylke']?.name;
          } else if (this.selectedGroups['Faggruppe kommune']) {
            this.selectedFaculty = this.selectedGroups['Faggruppe kommune']?.name;
          } else {
            this.selectedFaculty = null;
          }

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
      async addUserGroups() {
        if(this.courseId == -1) {
          this.groupResult = this.ADDTO_GROUPS_FAILED;
          return;
        }
        if (this.isGroupSelected) {
          let institution = null;
          if(this.institutionType == "school") {
            institution = this.selectedGroups?.Skole;
          } else if(this.institutionType == "kindergarten") {
            institution = this.selectedGroups?.Barnehage;
          }

          const params = Object.assign({},
            this.groups, {
            cookie: window.cookie,
            role: this.isLeader ? import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE : import.meta.env.VITE_CANVAS_STUDENT_ROLE_TYPE,
            faculty: this.selectedFaculty,
            county: this.selectedGroups?.Fylke,
            community: this.selectedGroups?.Kommune,
            currentGroups: this.initGroups,
            courseId: this.courseId,
            ...(institution ? { institution } : {})
          });

          try {
            await api.post('/group/user/bulk', params);
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
          const enrollmentRes = await api.post('/enrollment', {
            role: this.isLeader ? import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE : import.meta.env.VITE_CANVAS_STUDENT_ROLE_TYPE,
            cookie: window.cookie,
          });

          if (enrollmentRes.data.status != 200) {
            this.reportError("roleError", enrollmentRes.data.result);
          } else {
            this.clearError("roleError");
            this.enrollResult = this.ENROLLED;
          }         
        } catch(e) {
          this.reportError("roleError", "Kunne ikke oppdatere rollen.");
        }

        this.iframeresize();
      },
      async enroll() {
        if(this.isLoading || !this.isUpdateReady) return;

        this.isLoading = true;
        this.enrollResult = 0;
        this.groupResult = 0;

        try {
          await this.enrollUser();
          await this.addUserGroups();
          this.information = this.isLeader ? this.getPrincipalInformation() : this.getParticipantInformation();
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
      },
      async getRole() {
        try {
          const result = await api.get('/enrollment/', { params: { cookie: window.cookie } });
          if(result.data.result.length === 0) {
            this.getRoleResult = this.ENROLL_GET_FAILED;
            return;
          }

          this.isLeader = result.data.result.find(enrollment => enrollment.role === import.meta.env.VITE_CANVAS_PRINCIPAL_ROLE_TYPE) != null;
          this.information = this.isLeader ? this.getPrincipalInformation() : this.getParticipantInformation();
          this.clearError("roleError");
        } catch(e) {
          this.getRoleResult = this.ENROLL_GET_FAILED;
          this.isLeader = false;
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
          this.updateSelectedGroups();
          this.clearError("groupError");
        } catch(e)
        {
          this.reportError("groupError", "Kunne ikke hente gruppekategorier.");
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

    },
    updated() {
      this.iframeresize();
    },
    async created() {
      this.ENROLLED = 1;
      this.ADDED_TO_GROUPS = 2;
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
            self.updateSelectedGroups();
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
      await Promise.all([self.getGroups(), self.getFaculties(), self.getRole(), self.getInstitution()]).then(() => {
        console.log("All promises resolved.");
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
