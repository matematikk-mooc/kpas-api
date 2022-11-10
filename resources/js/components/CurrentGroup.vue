<template>

    <ul class="list-group mt-3 mb-3" v-if="dataLoaded && data.length > 0">
      <p>your groups</p> 
        <li
          class="list-group-item"
          v-for="(group, name) in data"
          v-if="group"
          :key="name"
        >
          {{ group.name }}
        </li>
        <div></div>
    </ul>
    <div v-else-if="dataLoaded && data.length === 0"
        class="alert alert-warning">Du er ikke med i noen grupper. <p>For å være med i gruppediskusjoner må du velge din tilhørighet lenger ned på denne siden.</p>
    </div>
    <p v-else>
      Laster inn dine grupper...
    </p>

</template>

<script>
import { reactive } from 'vue';

  export default {
    name: "CurrentGroup",

    props: {
      groups: Object,
      groupsLoaded: Boolean
    },
    data() {
      return {
        data : this.groups,
        dataLoaded : this.groupsLoaded
      }
    }, 
    watch: {
        groups() {
          this.data = this.groups;
        }, 
        groupsLoaded() {
          this.dataLoaded = this.groupsLoaded;
        }

    }
  }
</script>
