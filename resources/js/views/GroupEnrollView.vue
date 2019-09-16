<template>
  <div>
      <role-selector
        v-model="role"
      ></role-selector>
      <current-group
        :groups="currentGroups"
      ></current-group>
      <group-selector
        v-model="groups"
      ></group-selector>
      <button
        class="btn btn-primary"
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


  export default {
    name: "GroupEnrollView",

    components: {
      RoleSelector,
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

  $(document).ready(function() {
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

</script>
