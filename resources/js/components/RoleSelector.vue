<template>
  <div>
      <h2>Velg ny rolle og/eller grupper nedenfor</h2>
      <input type="radio" id="radioSkoleleder" name="role" value="1" v-model="wantToBePrincipal">
      <label for="radioSkoleleder">Skoleeier/-leder</label>
      <br>
      <input type="radio" id="deltager" name="role" value="0" v-model="wantToBePrincipal">
      <label for="deltager">Deltager</label>
    </div>
</template>

<script>
  export default {
    name: "RoleSelector",

    data() {
      return {
        wantToBePrincipal: 0,
      };
    },
  
    watch: {
      wantToBePrincipal(value) {
        this.$emit('input', value);
      },
    },
  }
</script>
