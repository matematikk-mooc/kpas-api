<template>
    <div>
      <h1>User History</h1>

    </div>
  </template>
  
  <script>
  import api from "../api";
  
  export default {
    name: "UserHistoryView",
    components: {
    },
    data() {
      return {
        history_data : null
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
            var userId = "317203";
            var fromDate = "2022-10-01";
            var toDate = this.getTodaysDate();
            var url = "/user/" + userId + "/history/?from=" + fromDate + "&to=" + toDate;
            const apiResult = await api.get(url, {
            params: { cookie: window.cookie }
            });
            console.log(apiResult.statusText)
            this.matomo_data = JSON.parse(apiResult.data.result);
        } catch(e)
        {
            console.log("Could not get user history data.");
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