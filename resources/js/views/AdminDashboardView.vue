@extends('layouts.app')
<template>
  <h2>Admin dashboard view</h2>
  <p>{{ studentCount }}</p>
</template>

<script>
import api from "../api";
export default {
  name: "AdminDashboardView",
  data() {
    return {
      studentCount: null,
      groupId: null,
    }
  },
  props: {
    settings: {},
    likert5ops: {},
  },
  methods: {
    async updateStudentCount() {
      try {
        let url;
        if (this.groupId) { 
          url = "/group/" + this.groupId + '/count';
        } else { 
          url = "/course/" + this.settings.custom_canvas_course_id + '/count';
        };

        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });

        this.studentCount = await apiResult.data.result;
      } catch(e) {
        console.error("Could not get student count.", e);
      }
    },
  },
  async created() {
    await this.updateStudentCount();
  },
};
</script> 

<style>

</style>
