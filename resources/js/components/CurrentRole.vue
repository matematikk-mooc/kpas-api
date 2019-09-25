<template>
    <div v-if="isPrincipal">
        Du er registrert som skoleeier-/leder.
    </div>
    <div v-else>
        Du er registrert som deltager.
    </div>
</template>

<script>
  export default {
    name: "CurrentRole",

    props: {
        isPrincipal: Boolean,
    },
  }
</script>
