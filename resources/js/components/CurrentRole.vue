<template>
  <Message type="default">
      <span v-html="information"></span>
  </Message>
</template>

<script>
import Message from './Message.vue';
export default {
  name: "CurrentRole",
  components: {
      Message
    },
  props: {
      isPrincipal: Boolean,
      information: String
  },
  created() {
    console.log("isPrincipal: ", this.isPrincipal);
    console.log("information: ", this.information);
  }
}
</script>
