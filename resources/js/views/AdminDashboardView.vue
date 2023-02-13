@extends('layouts.app')
<template>
  <h2>Admin dashboard view</h2>

</template>

<script>
import api from "../api";
export default {
  name: "AdminDashboardView",
  props: {
    settings: {},
    likert5ops: {}
  }
  
};
</script> 

<style>

</style>
