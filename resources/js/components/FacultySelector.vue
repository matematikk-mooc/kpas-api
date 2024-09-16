<template>
  <div class="faculty-selector" v-bind:style=" chosenFaculty || !faculties.length ? 'border: none;' : 'padding: 10px; border: 1px solid red;' ">
    <div class="faculty-item"
      v-for="faculty in faculties"
      :key="faculty"
    >
    <Message type="default">
      <label>
        <input type="radio" name="faculty" :value="faculty" :checked="isFacultySelected(faculty)" @change="handleFacultySelect(faculty)" />

        {{ faculty }}
      </label>
    </Message>
    </div>
  </div>
</template>

<script>
import Message from './Message.vue';
  export default {
    name: "FacultySelector",
    components: {
      Message
    },
    props: {
      faculties: { type: Array, default: () => [] },
      selectedFaculty: Object
    },
    computed: {
      chosenFaculty() {
        return this.selectedFaculty != null;
      }
    },
    methods: {
      isFacultySelected(faculty) {
        return this.selectedFaculty?.startsWith(faculty) ?? false;
      },
      handleFacultySelect(faculty) {
        this.$emit('updateSelectedFaculty', faculty);
      }
    }
  }
</script>

<style scoped>
  .message {
    padding: 0;
    margin: 5px;
    width: 100%;
  }
  
  label {
    margin: 0;
    display: inline-block;
    width: 100%;
  }

  .faculty-selector {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    gap: 10px;
  }

  .faculty-selector .message {
    margin: 0px;
  }

  .faculty-item {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    max-width: 300px;
    min-width: 250px;
    width: 100%;
  }
</style>