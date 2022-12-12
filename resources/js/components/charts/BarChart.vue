<template>  
  <div :id=this.id></div>
</template>

<script>
import { scaleLinear, scaleBand } from "d3-scale";
import { max } from "d3-array";
import { select } from "d3-selection";
import {axisBottom, axisLeft} from "d3-axis";
import "d3-transition";

export default {
  name: 'BarChart',
  props: {
    data: [],
    id: "",
    svgHeight: 0, 
    svgWidth: 0
  },
  mounted() {
    this.createChart();
  },
  methods: {
    
    createChart() {
      // margins and dimensions, possible props
      var margin = {top: 20, right: 20, bottom: 30, left: 40},
      width = this.svgWidth - margin.left - margin.right,
      height = this.svgHeight - margin.top - margin.bottom;
      
      // Set range based on margins and dimensions
      var xAxis = scaleBand()
      .range([0, width])
      .padding(0.1);
      var yAxis = scaleLinear()
      .range([height, 0]);
      var svg = select("#" + this.id).append("svg")
      .attr("preserveAspectRatio", "xMinYMin meet")
      .attr("viewBox", "0 0 600 400")
      .classed("svg-content-responsive", true)
      .append("g")
      .attr("transform", 
      "translate(" + margin.left + "," + margin.top + ")");
    
      var chartData = this.data
      
      // Scale the range of the data in the domains
      xAxis.domain(chartData.map(function(d) { return d.text; }));
      yAxis.domain([0, max(chartData, function(d) { return d.responses; })]);

      
      // Append the bars
      svg.selectAll(".barwrapper")
      .data(chartData)
      .enter().append("g")
      .attr("class", "barwrapper")
      .append("rect")
      .attr("x", function(d) { return xAxis(d.text); })
      .attr("width", xAxis.bandwidth())
      .attr("y", function(d) { return yAxis(d.responses); })
      .attr("height", function(d) { return height - yAxis(d.responses); })
      .attr("class", "bar")

      //Append values on top of bars
      svg.selectAll(".barwrapper").append("text")
      .attr("class", "bartext")
      .attr("x", function(d) { return xAxis(d.text) + xAxis.bandwidth()/2; })
      .attr("y", function(d) { return yAxis(d.responses) -  10; })
      .text(function(d) { return d.responses; });
    
      // X-axis
      svg.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(axisBottom(xAxis));
      
      // Y-axis
      svg.append("g")
      .call(axisLeft(yAxis));


      this.$parent.iframeresize();
    },

    resize(){
      
    }
  }
}
</script>

<style>

.bar {
  fill: #6d889d;
}

</style>
