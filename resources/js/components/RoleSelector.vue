<template>
  <div>
    <Message type="default">
      <span>
        <input type="radio" id="radioSkoleleder" name="role" :value=true v-model="wantToBePrincipal" @input="$emit('update:modelValue', true)">
        <label for="radioSkoleleder" class="role-label">{{leaderDescription}}</label>
      </span>
      <p>Når du velger rollen som eier/leder får du tilgang til hele kompetansepakken, både de modulene som er tilpasset eier/leder og for de ansatte. Vi anbefaler at du går igjennom eier/leder og modul 1-4 slik at du blir godt kjent med innholdet i kompetansepakken.</p>
    </Message>
    <Message type="default">
      <span>
        <input type="radio" id="deltager" name="role" :value=false v-model="wantToBePrincipal" @input="$emit('update:modelValue', false)">
        <label for="deltager" class="role-label ">{{participantDescription}}</label>
      </span>
      <p>Alle ansatte som ikke har eier/leder-rolle velger rollen som deltaker. Alle deltakere har anledning til å endre sin rolle senere.</p>
    </Message>

    </div>
</template>

<script lang="js">
import Message from './Message.vue';
  export default {
  name: "RoleSelector",
  components: {
      Message
    },
    props: {
        isPrincipal: Boolean,
        institutionType: String,
        leaderDescription: String,
        participantDescription: String,
        modelValue: Boolean
    },
    data() {
      return {
        wantToBePrincipal: false,
      }
    },
    computed: {
      principalWarning() {
        if(this.institutionType == "school") {
          return "NB! Dersom du er skoleeier kan du velge tilhørighet til Annet/Annen fylke/kommune/skole.";
        } else if(this.institutionType == "kindergarten") {
          return "NB! Dersom du er barnehageeier kan du velge tilhørighet til Annet/Annen fylke/kommune/barnehage.";
        }
        return "";
      }
    },
    created() {
      this.wantToBePrincipal = this.isPrincipal;
      this.$parent.iframeresize();
    },
    emits: ['update:value'],
    watch: {
      isPrincipal(value) {
        this.wantToBePrincipal = value;
        this.$parent.iframeresize();
      },
    }
  }
</script>
<style scoped>
  .role-label {
    font-weight: bold;
    font-size: 18px;
  }
</style>