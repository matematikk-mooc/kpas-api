<template>
  <div>
      <span v-html="information"></span>
  </div>
</template>

<script>
export default {
  name: "CurrentRole",
  props: {
      isPrincipal: Boolean,
      information: String
  }
}
</script>
