<template>

    <ul class="list-group mt-3 mb-3" v-if="groupsLoaded && sorted.length">
        <li
          class="list-group-item"
          v-for="group in sorted"
        >
          {{ group.category }} : {{group.name}}
        </li>
        <div></div>
    </ul>
    <message type="warn" v-else-if="groupsLoaded && !sorted.length"
       >Du er ikke med i noen grupper. <p>For å være med i gruppediskusjoner må du velge din tilhørighet lenger ned på denne siden.</p>
    </message>
    <p v-else>
      Laster inn dine grupper...
    </p>

</template>

<script>
import message from './Message.vue';
  export default {
    name: "CurrentGroup",
    components: {
      message
    },
    props: {
      groups: Object,
      groupsLoaded: Boolean
    },
    data() {
      return {
        sorted: []
      }
    },
    methods: {
      groupsSort() {
        let sortedGroups = []
        if(this.groups["Fylke"]) { sortedGroups.push({...this.groups["Fylke"], ...{"category" : "Fylke"}}) };
        if(this.groups["Kommune"]) { sortedGroups.push({...this.groups["Kommune"], ...{"category" : "Kommune"}}) };
        if(this.groups["Skole"]) { sortedGroups.push({...this.groups["Skole"], ...{"category" : "Skole"}}) };
        if(this.groups["Barnehage"]) { sortedGroups.push({...this.groups["Barnehage"], ...{"category" : "Barnehage"}}) };
        if(this.groups["Leder/eier (fylke)"]) { sortedGroups.push({...this.groups["Leder/eier (fylke)"], ...{"category" : "Leder/eier (fylke)"}}) };
        if(this.groups["Leder/eier (kommune)"]) { sortedGroups.push({...this.groups["Leder/eier (kommune)"], ...{"category" : "Leder/eier (kommune)"}}) };
        if(this.groups["Faggruppe nasjonalt"]) { sortedGroups.push({...this.groups["Faggruppe nasjonalt"], ...{"category" : "Faggruppe nasjonalt"}}) };
        if(this.groups["Faggruppe fylke"]) { sortedGroups.push({...this.groups["Faggruppe fylke"], ...{"category" : "Faggruppe fylke"}}) };
        if(this.groups["Faggruppe kommune"]) { sortedGroups.push({...this.groups["Faggruppe kommune"], ...{"category" : "Faggruppe kommune"}}) };
        this.sorted = sortedGroups

      }

    },
    watch: {
      groups() {
        this.groupsSort()
      }
    },
    mounted() {
      this.groupsSort()
    }
  }
</script>
