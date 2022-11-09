<template>
    <div>
        {{information}}
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
