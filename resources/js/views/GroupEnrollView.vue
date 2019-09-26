<template>
  <div>
      <h2>Din rolle</h2>
      <current-role
        :isPrincipal="isPrincipal"
      ></current-role>

      <hr/>
      <h2>Dine grupper</h2>
      <current-group
        :groups="currentGroups"
      ></current-group>
      <faculty-selector
        :faculties="faculties"

        v-model="faculty"
      />
      <role-selector
        :studentText="studentText"
        :principalText="principalText"
        v-model="wantToBePrincipal"
      ></role-selector>

      <group-selector
        v-model="groups"
      ></group-selector>
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
      <span v-if="isLoading" class="ml-3">Oppdaterer din rolle og gruppetilhørighet. Dette kan ta litt tid. Ikke lukk nettleseren.</span>
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
        return this.groupsAreSet && (this.faculties.length === 0 || this.faculty !== null);
      },
      studentText() {
        return "deltager"; 
      },
      principalText() {
        return "skoleeier/-leder";
      }
    },
    data() {
      return {
        isPrincipal: false,
        wantToBePrincipal: false,
        groups: [],
        currentGroups: null,
        faculties: [],
        isLoading: false,
        faculty: null,
      }
    },
    
    methods: {
      iframeresize() {
        var h = $("body").height();
        parent.postMessage(JSON.stringify({ subject:"lti.frameResize", height: h }), "*");
      },
      getRoleText(isPrincipal) {
        return isPrincipal ? "skoleeier/-leder" : "deltager";
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
        if(this.categories && this.usersGroups) {
          this.currentGroups = this.categorizeGroups(this.usersGroups, this.categories);
          this.$nextTick(function () {
            // DOM updated
            this.iframeresize();
          });
        } else if(this.categories) {
          console.log("groups not ready yet.");
        } else {
          console.log("categories not ready yet.");
        }
      },
      categorizeGroups(groups, categories) {
        var result = {};
        groups.forEach(function(group) {
          var category = categories.find(category => category.id == group.group_category_id);   
          result[category.name] = group;
        }); 
        return result;
      },
      async addUserGroups() {
        if (this.groupsAreSet) {
          const params = Object.assign({}, this.groups, {
            cookie: window.cookie,
            role: this.wantToBePrincipal ? process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE : process.env.MIX_CANVAS_STUDENT_ROLE_TYPE,
            faculty: this.faculty,
            currentGroups: this.currentGroups
          });
          await api.post('/group/user/bulk', params);
        }
      },
      async enrollUser() {
        await api.post('/enrollment', {
          role: this.wantToBePrincipal ? process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE : process.env.MIX_CANVAS_STUDENT_ROLE_TYPE,
          cookie: window.cookie,
        });
        this.isPrincipal = !this.isPrincipal;
      },
      async enroll() {
        console.log("WTBP:" + this.wantToBePrincipal);
        if (this.isReady) {
          this.isLoading = true;
          try {
            await this.enrollUser();
            await this.addUserGroups();
            this.isPrincipal = !this.isPrincipal;
          } catch (e) {
          } finally {
            this.isLoading = false;
            this.getUsersGroups();
          }
        }
      },
      async getRole() {
        const result = await api.get('/enrollment/', {
          params: { cookie: window.cookie }
        });
        this.isPrincipal = result.data.result.find(enrollment => enrollment.role === process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE);
        this.wantToBePrincipal = this.isPrincipal;
      },
      async getGroups() {
        const response = await api.get('/group/user', {
          params: {
            cookie: window.cookie,
          }
        });
        this.categories = response.data.result;
        console.log("Categories received.");
        this.updateCurrentGroups();
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
    async mounted() {
      var self = this;
      $(document).ready(function() {
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
      });
      self.getUsersGroups();
    },
    async created() {
      console.log("LTI listening for messages from parent.");
      window.addEventListener('message', function(evt) {
        try {
          var msg = JSON.parse(evt.data);
          if(msg.subject == "kpas-lti.usergroups") {
            console.log("Storing groups.");
            self.usersGroups = msg.groups;
            self.updateCurrentGroups();
          } else if(msg.subject == "kpas-lti.ltiparentready") {
              self.getUsersGroups();
          }
        } catch(e) {
          console.log("kpas-lti: exception parsing message " + e);
          console.log("When processing " + evt.data);
        }
      }, false);

      if (window.cookie === '') {
        console.log("No cookie, reloading KPAS-LTI.")
        window.location.reload();
      } else {
        var self = this;
        console.log("KPAS-LTI cookie found.")
        console.log("Hent kategorier...");
        await Promise.all([self.getGroups(), self.getFaculties()]);
      }
    },
  }
</script>