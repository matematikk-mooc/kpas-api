<template>
    <div>
      <h1>Modul statistikk</h1>
      <li v-for="item in matomo_data">
        <a> Url: {{item.url}}</a> 
        <p> Segment: {{item.segment}}</p>
        <p> Date: {{item.date}}</p>
        <p> Visits: {{item.visits}}</p>
        <p> Average time spent: {{item.average_time_spent}}</p>
      </li>
    </div>
  </template>
  
  <script>
  import api from "../api";
  
  export default {
    name: "MatomoView",
    components: {
    },
    data() {
      return {
        matomo_data : null
      };
    },
    methods: {
      iframeresize() {
        this.$nextTick(function () {
          var h = $("body").height() + 120;
          parent.postMessage(
            JSON.stringify({ subject: "lti.frameResize", height: h }),
            "*"
          );
        });
      },
      getTodaysDate() {
          var local = new Date();
          local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
          return local.toJSON().slice(0,10);
      },
      async getMatomoData() {
        try {
            var courseId = "502";
            var fromDate = "2022-10-01";
            var toDate = this.getTodaysDate();
            var url = "/course/" + courseId + "/pages/?from=" + fromDate + "&to=" + toDate;
            const apiResult = await api.get(url, {
            params: { cookie: window.cookie }
            });
            console.log(apiResult.statusText)
            this.matomo_data = JSON.parse(apiResult.data.result);
        } catch(e)
        {
            console.log("Could not get matomo data.");
        }
      }
  
    },
    async created() {
      this.iframeresize();
      const mql = window.matchMedia('(max-width: 400px)');
      var self = this;
      mql.onchange = (e) => { 
        self.iframeresize();
      }
      this.getMatomoData()
    },
  };
  </script>