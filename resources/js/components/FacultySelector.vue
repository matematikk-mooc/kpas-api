<template>
  <div>
    <div
      v-for="faculty in faculties"
      :key="faculty"
    >
      <label>
        <input
          name="faculty"
          type="radio"
          :value="faculty"

          v-model="chosenFaculty"
        />
        {{ faculty }}
      </label>
    </div>
  </div>
</template>

<script>
  export default {
    name: "FacultySelector",

    props: {
      faculties: { type: Array, default: () => [] },
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
