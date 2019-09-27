<template>

    <ul class="list-group mt-3 mb-3" v-if="groupsLoaded && Object.keys(groups).length">
        <li
          class="list-group-item"
          v-for="(group, name) in groups"
          v-if="group"
          :key="name"
        >
            <strong>{{ name }}:</strong> {{ group.name.split(':')[group.name.split(':').length - 1] }}
        </li>
        <div 
    </ul>
    <div v-else-if="groupsLoaded && !Object.keys(groups).length"
        class="alert-warning">Du er ikke med i noen grupper. For å være med i gruppediskusjoner må du velge din tilhørighet lenger ned på denne siden.
    </div>
    <p v-else>
      Laster inn dine grupper...
    </p>  

</template>

<script>
  export default {
    name: "CurrentGroup",

    props: {
      groups: Object,
      groupsLoaded: Boolean
    },
  }
</script>
