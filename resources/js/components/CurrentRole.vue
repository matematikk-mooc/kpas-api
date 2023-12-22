<template>
  <message type="default">
      <span v-html="information"></span>
  </message>
</template>

<script>
import message from './Message.vue';
export default {
  name: "CurrentRole",
  components: {
      message
    },
  props: {
      isPrincipal: Boolean,
      information: String
  }
}
</script>
