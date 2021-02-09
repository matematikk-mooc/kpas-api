<template>

    <ul class="list-group mt-3 mb-3" v-if="conflictsLoaded && Object.keys(conflicts).length">
        <li
          class="list-group-item"
          v-for="conflict in conflicts"
        >
            <strong>{{ conflict.courseName }}</strong> 
        </li>
        <div 
    </ul>
    <div v-else-if="conflictsLoaded && !Object.keys(conflicts).length"
        class="alert alert-info">Ingen konflikter funnet.</p>
    </div>
</template>

<script>
  export default {
    name: "MergeUserConflicts",
    props: {
      conflicts: Array,
      conflictsLoaded: Boolean
    },
  }
</script>
