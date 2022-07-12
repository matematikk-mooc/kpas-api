<template>
  <div>
    <h1>Statistikk VUE</h1>
    <D3BarChart :config="chart_config" :datum="chart_data"></D3BarChart>
  </div>
</template>

<script>
import api from "../api";
import { D3BarChart } from 'vue-d3-charts';

export default {
  name: "StatisticsView",
  components: {
    D3BarChart,
  },
  data() {
    return {
      chart_data: [
        //...
        {hours: 1648, production: 9613, year: '2007'},
        {hours: 2479, production: 6315, year: '2008'},
        {hours: 3200, production: 2541, year: '2009'}
      ],
      chart_config: {
        orientation: "horizontal",
        key: 'year',
        currentKey: '2004',
        values: ['hours'],
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
  },
  async created() {
    this.iframeresize();
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => { 
      self.iframeresize();
    }
  },
};
</script>