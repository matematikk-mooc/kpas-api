<template>
	<h2>Vurderingen av modulen med tanke på: </h2>
	<div id="gbc"></div>
	<section>
		<subsection class="category"><b>Læring</b></subsection>
		<subsection class="category"><b>Relevans</b></subsection>
		<subsection class="category"><b>Praksisendring</b></subsection>
	</section>
</template>

<script>
import { scaleLinear, scaleBand } from "d3-scale";
import { max } from "d3-array";
import { select } from "d3-selection";
import {axisBottom, axisLeft, tickSizeOuter} from "d3-axis";
import * as d3 from "d3";

export default {
	name: 'BarChart',
	props: {
		id: "",
	},
	data(){
		return {
			colors: {"læring" : "#6d889d", "relevans" : "#7dbf9d", "praksisendring" : "#eed0c3"}
		}
	},
	mounted() {
		this.createChart();
	},
	
	methods: {
		
		createChart() {

			let submission_data = [
				{
					"question":"læring",
					"Value1":19,
					"Value2":7,
					"Value3":4, 
					"Value4":3, 
					"Value5":9,
					"Ikke relevant":2
				},
				{
					"question":"relevans",
					"Value1":1,
					"Value2":3,
					"Value3":4, 
					"Value4":3, 
					"Value5":19,
					"Ikke relevant":2

				},
				{
					"question":"praksisendring", 
					"Value1":5,
					"Value2":0,
					"Value3":4, 
					"Value4":3, 
					"Value5":20,
					"Ikke relevant":2

				}
			];
			
    	let margin = {top: 20, right: 25, bottom: 80, left: 25},
    	width = 800 - margin.left - margin.right,
  		height = 600 - margin.top - margin.bottom

			let svg = select("#gbc").append("svg")
      .attr("preserveAspectRatio", "xMinYMin meet")
      .attr("viewBox", "0 0 800 600")
      .classed("svg-content-responsive", true)

      let g = svg.append("g")
     	.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

			let subCategories = Object.keys(submission_data[0]).slice(1);

			let maxValue= max(submission_data, function(d){
    			return (Math.max(d.Value1, d.Value2, d.Value3, d.Value4, d.Value5, d["Ikke relevant"]))});
				
			//range and domain
    	let y = scaleLinear()
			.domain([0, maxValue+5])
			.range([height, 0]);
			
			let x0 = scaleBand()
			.rangeRound([0, width])
			.paddingInner(0.1)
			.paddingOuter(0.1);
			
			let x1 = scaleBand() 
			.paddingOuter(0.30)
			.paddingInner(0.15); 

			
			//bar colors
			let z = ["#6d889d", "#7dbf9d", "#eed0c3"];
			
			x0.domain(submission_data.map( d =>  d.question ));
			x1.domain(subCategories).rangeRound([0, x0.bandwidth()])
			let self = this;
			// Add bar chart
			var selection = g.selectAll("g")
			.data(submission_data)
			.enter().append("g")
			.attr("transform", d => "translate(" + x0(d.question) + ",0)" )

			selection.selectAll("rect")
			.data(function(d) { return subCategories.map(function(key) { return {key: key, value: d[key], color: self.colors[d.question]}; }); })
			.enter().append("rect")
			.attr("x", d => x1(d.key) )
			.attr("width", x1.bandwidth())
			.attr("y", d => y(d.value) )
			.attr("height", d => height - y(d.value))
			.attr("fill", d => d.color)

			//Value on top of bars
			selection.selectAll("text")
			.data(function(d) { return subCategories.map(function(key) { return {key: key, value: d[key]}; }); })
			.enter().append("text")
			.attr("x", d => x1(d.key) + x1.bandwidth()/2 )
			.attr("y", d => y(d.value) - 10)
			.text(d => d.value)
			
			//add the x-axis
			selection.append("g")
			.attr("class", "axis")
			.attr("transform", "translate(0," + height + ")")
			.attr("x", d => x0(d.question))
      .call(axisBottom(x1).tickSizeOuter(0))
      .selectAll("text")
      .attr("y", 15)
    	.attr("x", 0)
      .attr("dy", ".35em")
      .attr("transform", "rotate(50)")
      .style("text-anchor", "start");
			

		
			//y-axis
			g.append('g')
			.call(axisLeft(y))

			//x-axis continious line
			g.append("line")
    	.attr("y1", y(0))
    	.attr("y2", y(0))
  		.attr("x1", 0)
  		.attr("x2", width)
			.attr("stroke", "black");
			
		}
	}
}
</script>

<style>

section {
	width: 100%
}

.category {
	display: inline-block;
	width: 33%;
	text-align: center;
}
b {
	font-size: 10px;
	vertical-align: middle;
	margin: auto;
}
</style>
