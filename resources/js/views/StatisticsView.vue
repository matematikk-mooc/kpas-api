<template>
  <div>
    <h1>Statistikk VUE</h1>
    <h2> her vises statistikk </h2>
    <D3BarChart :config="chart_config" :datum="chart_data"></D3BarChart>
  </div>
</template>

<script>
import api from "../api";
import { D3BarChart } from 'vue-d3-charts';

//https://saigesp.github.io/vue-d3-charts/#/barchart

export default {
  name: "StatisticsView",
  components: {
    D3BarChart,
  },
  data() {
    return {
      chart_data: [
      ],
      chart_config: {
        orientation: "horizontal",
        key: 'activity_date',
        values: ['active_users_count'],
        axis: {
          yTicks: 3
        },
        color: {
          default: '#222f3e',
          current: '#41B882'
        }
      }
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
    async getUserActivity() {
      try {
        var courseId = 360;
        var from = "1970-01-01";
        var to = this.getTodaysDate();
        var url = "/user_activity/" + courseId + "?from=" + from + "&to=" + to;

        const apiResult = await api.get(url, {
          params: { cookie: window.cookie }
        });
        this.chart_data = apiResult.data;
        console.log(this.chart_data);

      } catch(e)
      {
        console.log("Could not get user activity.");
      }
    },

  },
  async created() {
    this.iframeresize();
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => { 
      self.iframeresize();
    }
    this.getUserActivity();
  },
};
</script>