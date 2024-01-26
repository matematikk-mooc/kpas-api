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
  }
}
</script>
