<template>

    <ul class="list-group mt-3 mb-3" v-if="groupsLoaded && Object.keys(groups).length"> 
        <li
          class="list-group-item"
          v-for="(group, name) in groups" 
        >
          {{ name }} : {{group.name}}
        </li>
        <div></div>
    </ul>
    <div v-else-if="groupsLoaded && !Object.keys(groups).length"
        class="alert alert-warning">Du er ikke med i noen grupper. <p>For å være med i gruppediskusjoner må du velge din tilhørighet lenger ned på denne siden.</p>
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
