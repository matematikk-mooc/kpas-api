<template>
  <div class="faculty-selector" v-bind:style=" chosenFaculty || !faculties.length ? 'border: none;' : 'padding: 10px; border: 1px solid red;' ">
    <div class="faculty-item"
      v-for="faculty in faculties"
      :key="faculty"
    >
    <Message type="default">
      <label>
        <input
          name="faculty"
          type="radio"
          :value="faculty"

          v-model="chosenFaculty"
          @input="this.$emit('update:modelValue', faculty)"
        />
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
      modelValue: String,
      currentFaculty: String
    },
    created() {
      console.log("current Faculty: ", this.currentFaculty);
      this.chosenFaculty = this.faculties.find(faculty => faculty === this.currentFaculty);
    },

    data() {
      return {
        chosenFaculty: null,
      }
    },

    watch: {
      chosenFaculty(value) {
        this.$emit('input', value);
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
  }
  .faculty-item {
    width: 50%;
    box-sizing: border-box;
    padding: 5px;
  }
  @media only screen and (max-width: 900px) {
    .faculty-item {
      width: 100%;
    }
    .faculty-selector {
      flex-direction: column;
    }
  }

</style>