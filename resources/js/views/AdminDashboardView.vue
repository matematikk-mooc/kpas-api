@extends('layouts.app')
<template>
  <h2>Admin dashboard view</h2>
  <DashboardGroupSelect 
    :settings=this.settings
		:categories=this.categories
    @update="updateGroupId"
  />
  <p>{{ groupId }}</p>
  <p>{{ studentCount }}</p>
</template>

<script>
import api from "../api";
import DashboardGroupSelect from "../components/DashboardGroupSelect";

export default {
  name: "AdminDashboardView",
  components: {
    DashboardGroupSelect,
  },
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
    async getStudentCount() {
      try {
        let url;
        if (this.groupId) { 
          url = "/group/" + this.groupId + '/count';
        } else { 
          url = "/course/" + this.settings.custom_canvas_course_id + '/count';
        };

        const response = await api.get(url, {
          params: { cookie: window.cookie }
        });

        this.studentCount = await response.data.result;
      } catch(e) {
        console.error("Could not get student count.", e);
      }
    },
  },
  async getGroupCategories() { // TODO: refactor this method to GroupSelector
    try {
      const response = await api.get('/group/user', {
        params: {
          cookie: window.cookie,
        }
      });
      this.categories = response.data.result;
    } catch(e){
      console.error("Could not get group categories.", e)
    }
  },
  updateGroupId(value) {
    this.groupId = value;
  },
  async created() {
    await this.getGroupCategories();
    await this.getStudentCount();
  },
};
</script> 

<style>

</style>
