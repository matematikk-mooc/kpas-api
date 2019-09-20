<template>
    <div>
        Du er registrert som {{ isPrincipal ? 'skoleleder' : 'deltager'}}
        <button
          class="btn btn-primary ml-1"
          @click="isPrincipal = !isPrincipal"
        >
            Bli {{ isPrincipal ? 'deltager' : 'skoleleder' }}
        </button>
    </div>
</template>

<script>
  import api from '../api';

  export default {
    name: "RoleSelector",

    data() {
      return {
        isPrincipal: false,
      };
    },

    async created() {
      const result = await api.get('/enrollment/', {
        params: { cookie: window.cookie }
      });
      this.isPrincipal = result.data.result.find(enrollment => enrollment.role === process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE);
    },

    watch: {
      isPrincipal(value) {
        this.$emit('input', value ? process.env.MIX_CANVAS_PRINCIPAL_ROLE_TYPE : process.env.MIX_CANVAS_STUDENT_ROLE_TYPE);
      },
    },
  }
</script>
