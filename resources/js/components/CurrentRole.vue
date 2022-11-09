<template>
    <div>
        <span>{{information}}</span>
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
