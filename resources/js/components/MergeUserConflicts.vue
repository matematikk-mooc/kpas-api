<template>
<message type="error" v-if="conflictsLoaded && Object.keys(conflicts).length">
<ul class="list-group mt-3 mb-3" v-if="conflictsLoaded && Object.keys(conflicts).length">
        <li
          class="list-group-item"
          v-for="conflict in conflicts"
        >
            <strong>{{ conflict.courseName }}</strong>
        </li>
    </ul>
</message >
    
<message  v-else-if="conflictsLoaded && !Object.keys(conflicts).length" type="info">
  Ingen konflikter funnet.
</message>
</template>

<script>
import message from './Message.vue';
  export default {
  name: "MergeUserConflicts",
  components: {
      message
    },
    components: {
      message
    },
    props: {
      conflicts: Array,
      conflictsLoaded: Boolean
    },
  }
</script>
