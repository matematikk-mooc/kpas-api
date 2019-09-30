<template>
  <div>
      <h2>Velg ny rolle og/eller grupper nedenfor</h2>
      <input type="radio" id="radioSkoleleder" name="role" v-bind:value="true" v-model="wantToBePrincipal">
      <label for="radioSkoleleder">Leder/eier</label>
      <br>
      <input type="radio" id="deltager" name="role" v-bind:value="false" v-model="wantToBePrincipal">
      <label for="deltager">Deltager</label>
    <div v-if="wantToBePrincipal" class="alert alert-info">NB! Dersom du er skoleeier må du velge tilhørighet til en gitt skole, 
      selv om du har ansvar for alle skolene. For ledere/eiere vil diskusjonen foregå på fylkesnivå, mens for lærere legges diskusjonen på kommunenivå.
    </div>

    </div>
</template>

<script>
  export default {
    name: "RoleSelector",
    props: {
        isPrincipal: Boolean
    },
    data() {
      return {
        wantToBePrincipal: false,
      }
    },
    created() {
      this.wantToBePrincipal = this.isPrincipal;
    },
    watch: {
      isPrincipal(value) {
        this.wantToBePrincipal = value;
      },
      wantToBePrincipal(value) {
        this.$emit('input', value);
      },
    },
  }
</script>
