<template>
    <ul>
        <li
          v-for="(group, name) in groups"
          v-if="group"
          :key="name"
        >
            {{ name }}: {{ group.name.split(':')[group.name.split(':').length - 1] }}
        </li>
    </ul>
</template>

<script>
  export default {
    name: "CurrentGroup",

    props: {
      groups: Object,
    },
  }
</script>
