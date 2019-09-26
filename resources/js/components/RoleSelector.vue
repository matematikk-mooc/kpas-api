<template>
  <div>
      <h2>Velg ny rolle og/eller grupper nedenfor</h2>
      <input type="radio" id="radioSkoleleder" name="role" v-bind:value="true" v-model="wantToBePrincipal">
      <label for="radioSkoleleder">Skoleeier/-leder</label>
      <br>
      <input type="radio" id="deltager" name="role" v-bind:value="false" v-model="wantToBePrincipal">
      <label for="deltager">Deltager</label>
    </div>
</template>

<script>
  export default {
    name: "RoleSelector",
    data() {
      return {
        wantToBePrincipal: false,
      }
    },
  
    watch: {
      wantToBePrincipal(value) {
        this.$emit('input', value);
      },
    },
  }
</script>
