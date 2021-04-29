<template>
  <div>
      <input type="radio" id="radioSkoleleder" name="role" v-bind:value="true" v-model="wantToBePrincipal">
      <label for="radioSkoleleder">Leder/eier</label>
      <br>
      <input type="radio" id="deltager" name="role" v-bind:value="false" v-model="wantToBePrincipal">
      <label for="deltager">Deltager</label>
    <div v-if="wantToBePrincipal" class="alert alert-info">{{principalWarning}}
    </div>

    </div>
</template>

<script>
  export default {
    name: "RoleSelector",
    props: {
        isPrincipal: Boolean,
        institution: String
    },
    data() {
      return {
        wantToBePrincipal: false,
      }
    },
    computed: {
      principalWarning() {
        if(this.institution == "school") {
          return "NB! Dersom du er skoleeier må du velge tilhørighet til en gitt skole, selv om du har ansvar for alle skolene.";
        } else if(this.institution == "kindergarten") {
          return "NB! Dersom du er barnehageeier må du velge tilhørighet til en gitt barnehage, selv om du har ansvar for alle barnehagene.";
        }
      }
    },
    created() {
      this.wantToBePrincipal = this.isPrincipal;
    },
    watch: {
      isPrincipal(value) {
        this.wantToBePrincipal = value;
        this.$parent.iframeresize();
      },
      wantToBePrincipal(value) {
        this.$emit('input', value);
      },
    },
  }
</script>
