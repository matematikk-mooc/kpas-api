@extends('layouts.app')
<template>
  <div>
    <h1>Rolle- og gruppeverktøy</h1>
    <a :href="urlRoleMode">Sett inn Rolle- og gruppeverktøy</a>
    
    <h1>Statistikk</h1>
    <a :href="urlStatisticsMode">Sett inn statistikkverktøy</a>

    <h1>Diplom</h1>
    Velg hvilke logoer som skal vises nederst på diplomet:

    <div v-for="logo in logoList">
        <label><img class="diplomaIssuedByImage" :src="'images/' + logo"></label>
        <input type="checkbox" v-model="logoSelected" :value="logo"/>
    </div> 
    <a :href="urlDiplomaMode">Sett inn Diplom</a>       
  </div>
</template>

<script>
import api from '../api';

export default {
  name: "Diploma",
  props: ['appurl', 'launchid', 'configdirectory', 'diplomamode', 'statisticsmode'],    
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
    },
    urlStatisticsMode: function () {
      return this.appurl + "/deep?launch_id=" + this.launchid + "&kpasMode=" + this.statisticsmode + "&config_directory=" + this.configdirectory;
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