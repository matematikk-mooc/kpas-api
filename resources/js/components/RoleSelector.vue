<template>
  <div>
      <input type="radio" id="radioSkoleleder" name="role" v-bind:value="true" v-model="wantToBePrincipal">
      <label for="radioSkoleleder">{{leaderDescription}}</label>
      <br>
      <input type="radio" id="deltager" name="role" v-bind:value="false" v-model="wantToBePrincipal">
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
    watch: {
      isPrincipal(value) {
        this.wantToBePrincipal = value;
        this.$parent.iframeresize();
      },
      wantToBePrincipal(value) {
        this.$emit('update:wantToBePrincipal', value);
        console.log("value: " + value)
        console.log("wanttobe: " + this.wantToBePrincipal);
      },
    },
  }
</script>
