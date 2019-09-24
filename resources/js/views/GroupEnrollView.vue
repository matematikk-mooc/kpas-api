<template>
  <div>
      <role-selector
        v-model="role"
      ></role-selector>
      <current-group
        :groups="currentGroups"
      ></current-group>
      <faculty-selector
        :faculties="faculties"

        v-model="faculty"
      />
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
        Bli med
      </button>
      <span v-if="isLoading" class="ml-3">Lasting....</span>
  </div>
</template>

<script>
  import api from '../api';
  import RoleSelector from "../components/RoleSelector";
  import GroupSelector from "../components/GroupSelector";
  import CurrentGroup from "../components/CurrentGroup";
  import FacultySelector from "../components/FacultySelector";
  export default {
    name: "GroupEnrollView",
    components: {
      RoleSelector,
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
      }
    },
    data() {
      return {
        role: process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE,
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
      getUsersGroups() {
        const getusergroupsMessage = {
          subject: 'kpas-lti.getusergroups'
        }
        window.parent.postMessage(JSON.stringify(getusergroupsMessage), "*");        
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
            role: this.role,
            faculty: this.faculty,
          });
          await api.post('/group/user/bulk', params);
        }
      },
      async enrollUser() {
        await api.post('/enrollment', {
          role: this.role,
          cookie: window.cookie,
        });
      },
      async enroll() {
        if (this.isReady) {
          this.isLoading = true;
          try {
            await this.enrollUser();
            await this.addUserGroups();
          } catch (e) {
          } finally {
            this.isLoading = false;
            this.getUsersGroups();
          }
        }
      },
      async getGroups() {
        const response = await api.get('/group/user', {
          params: {
            cookie: window.cookie,
          }
        });
        this.categories = response.data.result;
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
      await Promise.all([self.getGroups(), self.getFaculties()]);
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
        console.log("Get users groups.");
        self.getUsersGroups();
      });
    },
    async created() {
      var self = this;
      window.addEventListener('message', function(e) {
        try {
          var msg = JSON.parse(e.data);
          if(msg.subject == "kpas-lti.usergroups") {
            self.currentGroups = self.categorizeGroups(msg.groups, self.categories);
            this.$nextTick(function () {
              // DOM updated
              this.iframeresize();
            });
          }
        } catch(e) {
          console.log("kpas-lti: ignoring message " + e. data);
        }
      }, false);

      if (window.cookie === '') {
        console.log("No cookie, reloading KPAS-LTI.")
        window.location.reload();
      } else {
        console.log("KPAS-LTI cookie found.")
      }
    },
  }
</script>