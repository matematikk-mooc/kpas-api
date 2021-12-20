<template>
  <div>
    <h1><a :href="urlRoleMode">Sett inn Rolle- og gruppeverktøy</a></h1>
    <h1><a :href="urlDiplomaMode">Sett inn Diplom</a></h1>

    <div v-for="logo in logoList">
        <label>{{logo}}</label>
        <input type="checkbox" v-model="logoSelected" :value="logo"/>
    </div>        

    <br>
    <span>Checked names: {{ logoSelected }}</span>
  </div>
</template>

<script>
import api from '../api';

export default {
  name: "Diploma",
  props: ['appurl', 'launchid', 'configdirectory', 'diplomamode'],    
  data() {
    return {
      logoList: [],
      logoSelected: []
    };
  },
  computed: {
    urlRoleMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&config_directory=" + this.configdirectory;
    },
    urlDiplomaMode: function () {
      var url = this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.diplomamode + "&config_directory=" + this.configdirectory;
      this.logoSelected.forEach(function addLogo(logo) {
        url += "&logo[]=" + logo; 
      });
    return url;
    }
  },  
  methods: {
    async getLogoList() {
        const response = await api.get('/diploma/logolist');
        this.logoList = response.data.result;
    },
    async fetchLogoList() {
    },
  },
  async created() {
      await Promise.all([this.getLogoList()]);
  },
};
</script>