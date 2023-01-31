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
import { select, selectAll } from "d3-selection";
import {axisBottom, axisLeft, tickSizeOuter} from "d3-axis";

export default {
	name: 'BarChart',
	props: {
		id: "",
		data: [],
		likert5ops: {}
	},
	data(){
		return {
			colors: {
				"læring" : "#6d889d",
				"relevans" : "#7dbf9d",
				"praksisendring" : "#eed0c3"
			},
			chartData: [],
			likert5ops: this.likert5ops,
			maxValue: 0
		}
	},
	mounted() {
		this.createChart();
		
	},
	
	methods: {
		createChart() {
			this.mapData(); 
			
    	let margin = {top: 20, right: 25, bottom: 125, left: 25},
    	width = 800 - margin.left - margin.right,
  		height = 600 - margin.top - margin.bottom

			let svg = select("#gbc").append("svg")
      .attr("preserveAspectRatio", "xMinYMin meet")
      .attr("viewBox", "0 0 800 600")
      .classed("svg-content-responsive", true)

      let g = svg.append("g")
     	.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

			let questions = Object.keys(this.chartData[0]).slice(1);

			//range and domain
    	let y = scaleLinear()
			.domain([0, this.maxValue+1])
			.range([height, 0]);
			
			let x0 = scaleBand()
			.rangeRound([0, width])
			.paddingInner(0.1)
			.paddingOuter(0.1);
			
			let x1 = scaleBand() 
			.paddingOuter(0.30)
			.paddingInner(0.15); 

			const yAxis = axisLeft(y).ticks(7);

			x0.domain(this.chartData.map( d =>  d.question ));
			x1.domain(questions).rangeRound([0, x0.bandwidth()])
			let self = this;
			// Add bar chart
			var selection = g.selectAll("g")
			.data(this.chartData)
			.enter().append("g")
			.attr("transform", d => "translate(" + x0(d.question) + ",0)" )

			selection.selectAll("rect")
			.data(function(d) { return questions.map(function(key) { return {key: key, value: d[key], color: self.colors[d.question]}; }); })
			.enter().append("rect")
			.attr("x", d => x1(d.key) )
			.attr("width", x1.bandwidth())
			.attr("y", d => y(d.value) )
			.attr("height", d => height - y(d.value))
			.attr("fill", d => d.color)

			//Value on top of bars
			selection.selectAll("text")
			.data(function(d) { return questions.map(function(key) { return {key: key, value: d[key]}; }); })
			.enter().append("text")
			.attr("x", d => x1(d.key) + x1.bandwidth()/2 )
			.attr("y", d => y(d.value) - 10)
			.text(d => {if (d.value != 0) { return d.value }})

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
			.call(yAxis);

			//x-axis continious line
			g.append("line")
    	.attr("y1", y(0))
    	.attr("y2", y(0))
  		.attr("x1", 0)
  		.attr("x2", width)
			.attr("stroke", "black");
			
		},
		mapData(){
			let cdata = [];
			let keys = Object.keys(this.colors)
			for(let i = 0; i < this.data.length; i++){
				let object = Object.assign({}, {"question" : keys[i]}, this.data[i].submission_data);
				cdata.push(object)
			}
			this.maxValue= max(cdata, function(d){
    		return (Math.max(
					d.likert_scale_5pt_1,
					d.likert_scale_5pt_2,
					d.likert_scale_5pt_3,
					d.likert_scale_5pt_4,
					d.likert_scale_5pt_5,
					d.likert_scale_5pt_none)
				)
			});

			const renameKeys = o => Object.assign(...Object.keys(o).map(k => ({ [this.likert5ops[k] || k]: o[k] })));
			this.chartData = cdata.map(renameKeys);
		}

	},
	watch: {
      data(){
		selectAll("svg").remove()
        this.createChart()
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
	font-size: 14px;
	vertical-align: middle;
	margin: auto;
}
</style>
