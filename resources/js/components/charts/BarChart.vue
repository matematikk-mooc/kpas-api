<template>
  <h2 class="qText" v-html="this.data.text"></h2>
  <div :id=this.id></div>
</template>

<script>
import { scaleLinear, scaleBand } from "d3-scale";
import { max } from "d3-array";
import { select } from "d3-selection";
import {axisBottom, axisLeft} from "d3-axis";
import * as d3 from "d3";

export default {
  name: 'BarChart',
  props: {
    data: {},
    id: "",
    svgHeight: 0, 
    svgWidth: 0, 
    svg: null
  },
  data() {
    return {
      dataArray: []
    }
  },
  mounted() {
    this.createChart();
  },

  methods: {
    mapData(){
      this.dataArray = Object.keys(this.data.submission_data).map((key) => Object({"option": key.split("_").pop() == 'none'? "Vet ikke" : key.split("_").pop(), "count":  parseInt(this.data.submission_data[key])}));

    },
    createChart() {
      this.mapData();
      // margins and dimensions, possible props
      let margin = {top: 20, right: 20, bottom: 30, left: 40},
      width = this.svgWidth - margin.left - margin.right,
      height = this.svgHeight - margin.top - margin.bottom;
      
      // Set range based on margins and dimensions
      let xAxis = scaleBand()
      .range([0, width])
      .padding(0.1);
      let yAxis = scaleLinear()
      .range([height, 0]);
      const svg = select("#" + this.id).append("svg")
      .attr("preserveAspectRatio", "xMinYMin meet")
      .attr("viewBox", "0 0 600 400")
      .classed("svg-content-responsive", true)
      .append("g")
      .attr("transform", 
      "translate(" + margin.left + "," + margin.top + ")");
    
      let chartData = this.dataArray; 
      
      // Scale the range of the data in the domains
      xAxis.domain(chartData.map(function(d) { return d.option; }));
      yAxis.domain([0, max(chartData, function(d) { return d.count; })]);

      const t = svg.transition().duration(750);

      // Append the bars
      svg.selectAll(".barwrapper")
      .data(chartData) 
      .enter().append("g")
      .attr("class", "barwrapper")
      .append("rect")
      .attr("x", function(d) { return xAxis(d.option); })
      .attr("width", xAxis.bandwidth())
      .attr("y", function(d) { return yAxis(d.count); })
      .attr("height", function(d) { return height - yAxis(d.count); })
      .attr("class", "bar")

      //Append values on top of bars
      svg.selectAll(".barwrapper").append("text")
      .attr("class", "bartext")
      .attr("x", function(d) { return xAxis(d.option) + xAxis.bandwidth()/2; })
      .attr("y", function(d) { return yAxis(d.count) -  10; })
      .text(function(d) { return d.count; });
    
      // X-axis
      svg.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(axisBottom(xAxis));
      
      // Y-axis
      svg.append("g")
      .call(axisLeft(yAxis));

    }
  },
    watch: {
      data(){
        const parent = document.getElementById(this.id)
        parent.removeChild(parent.children[0])
        this.createChart()
      }
  }
}
</script>

<style>

.bar {
  fill: #6d889d;
}

.qText {
  padding-top: .5em;
}

</style>
