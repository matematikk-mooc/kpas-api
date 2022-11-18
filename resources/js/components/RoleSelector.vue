<template>
  <div>
      <input type="radio" id="radioSkoleleder" name="role" :value=true v-model="wantToBePrincipal" @input="$emit('update:value', true)">
      <label for="radioSkoleleder">{{leaderDescription}}</label>
      <br>
      <input type="radio" id="deltager" name="role" :value=false v-model="wantToBePrincipal" @input="$emit('update:value', false)">
      <label for="deltager">{{participantDescription}}</label>
    <div v-if="wantToBePrincipal && institutionType" class="alert alert-info">{{principalWarning}}
    </div>

    </div>
</template>

<script lang="js">
  export default {
    name: "RoleSelector",
    props: {
        isPrincipal: Boolean,
        institutionType: String,
        leaderDescription: String,
        participantDescription: String,
        value: Boolean
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
    },
    emits: ['update:value'],
    watch: {
      isPrincipal(value) {
        this.wantToBePrincipal = value;
        console.log(this.isPrincipal)
        this.$parent.iframeresize();
      },
    } 
  }
</script>
