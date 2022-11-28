<template>
  <div>
      <input type="radio" id="radioSkoleleder" name="role" :value=true v-model="wantToBePrincipal" @input="$emit('update:modelValue', true)">
      <label for="radioSkoleleder">{{leaderDescription}}</label>
      <br>
      <input type="radio" id="deltager" name="role" :value=false v-model="wantToBePrincipal" @input="$emit('update:modelValue', false)">
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
