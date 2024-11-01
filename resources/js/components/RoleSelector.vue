<template>
  <div class="group-role-selector">
    <Message type="default">
      <span>
        <input type="radio" id="deltager" name="role" value="false" :checked="isLeader != true" @change="handleParticipantSelect">
        <label for="deltager" class="role-label ">{{ participantDescription }}</label>
      </span>
      
      <p>Velg denne rollen dersom du skal gjennomføre en kompetansepakke.</p>
    </Message>

    <Message type="default">
      <span>
        <input type="radio" id="radioSkoleleder" name="role" value="true" :checked="isLeader == true" @change="handleLeaderSelect">
        <label for="radioSkoleleder" class="role-label">{{ leaderDescription }}</label>
      </span>

      <p>Velg denne rollen dersom du skal ha tilgang til prossesstøtte for gjennomføring av kompetansepakken.</p>
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
        institutionType: String,
        leaderDescription: String,
        participantDescription: String,
        isLeader: Boolean
    },
    methods: {
      handleLeaderSelect() {
        if (!this.isLeader) this.$emit('updateIsLeader', true);
      },
      handleParticipantSelect() {
        if (this.isLeader) this.$emit('updateIsLeader', false);
      }
    }
  }
</script>
<style scoped>
  .role-label {
    font-weight: bold;
    font-size: 18px;
  }
</style>