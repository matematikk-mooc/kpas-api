<template>
  <div>
    <current-group
      :groups="currentGroups"
    ></current-group>
  </div>
</template>

<script>
  import api from '../api';
  import CurrentGroup from "../components/CurrentGroup";


  export default {
    name: "GroupEnrollView",

    components: {
      CurrentGroup,
    },

    data() {
      return {
        currentGroups: {},
      }
    },

    methods: {
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
