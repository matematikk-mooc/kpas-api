<template>
    <div v-if="isPrincipal">
        <span v-html="information"></span>
    </div>
    <div v-else>
        <span v-html="information"></span>
    </div>
</template>

<script>
  export default {
    name: "CurrentRole",
    props: {
        isPrincipal: Boolean,
        information: String
    },
  }
</script>
