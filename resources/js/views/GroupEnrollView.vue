<template>
  <div>
      <current-group
        :groups="currentGroups"
      ></current-group>
      <group-selector
        v-model="groups"
      ></group-selector>
      <span v-if="isLoading">Loading....</span>
      <button
        @click="enroll"
      >
        Bli med
      </button>
  </div>
</template>

<script>
  import api from '../api';
  import GroupSelector from "../components/GroupSelector";
  import CurrentGroup from "../components/CurrentGroup";


  export default {
    name: "GroupEnrollView",

    components: {
      GroupSelector,
      CurrentGroup,
    },

    computed: {
      groupsAreSet() {
        return Object.keys(this.groups).length
      },
    },

    data() {
      return {
        role: process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE,
        groups: {},
        currentGroups: {},
        isLoading: false,
      }
    },

    methods: {
      async addUserGroups() {
        if (this.groupsAreSet) {
          const params = Object.assign({}, this.groups, {
            cookie: window.cookie,
            role: this.role,
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
        this.isLoading = true;
        await Promise.all([this.addUserGroups(), this.enrollUser()]);
        await this.getGroups();
        this.isLoading = false;
      },

      async getGroups() {
        const response = await api.get('/group/user', {
          params: {
            cookie: window.cookie,
          }
        });
        this.currentGroups = response.data.result;
      },
    },

    async created() {
      await this.getGroups();
    },
  }
</script>
