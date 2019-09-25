<template>
    <ul class="list-group mt-3 mb-3" v-if="groups">
        <li
          class="list-group-item"
          v-for="(group, name) in groups"
          v-if="group"
          :key="name"
        >
            <strong>{{ name }}:</strong> {{ group.name.split(':')[group.name.split(':').length - 1] }}
        </li>
    </ul>
    <p v-else>
      Laster inn dine gruppemedlemskap...
    </p>  
</template>

<script>
  export default {
    name: "CurrentGroup",

    props: {
      groups: Object,
    },
  }
</script>
