<template>
	<div id="linechart"></div>
</template>

<script>
import * as d3 from "d3";
export default {
	name: "LineChart",
	
	props: {
		
	},
	data() {
		return {
			tooltip: null
		}
	},
	methods: {
		drawChart() {
			const data=  [{"date" : "2022-03-17", "value" : 5}, {"date" : "2022-03-18", "value" : 7}, {"date" : "2022-03-19", "value" : 4}, {"date" : "2022-03-20", "value" : 9}, {"date" : "2022-03-21", "value" : 5}]
			
			
			let margin = {top: 20, right: 25, bottom: 125, left: 25},
			width = 800 - margin.left - margin.right,
			height = 600 - margin.top - margin.bottom
			
			
			const svg = d3.select("#linechart")
			.append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", `translate(${margin.left},${margin.top})`)
			.on("pointerenter pointermove", pointermoved)
      .on("pointerleave", pointerleft)
      .on("touchstart", event => event.preventDefault());
			
			
			var dateParser = d3.timeParse("%Y-%m-%d");
			
			const xScale = d3.scaleTime()
			.domain(d3.extent(data, function(d) { return dateParser(d.date)}))
			.range([ 0, width ]);
			
			
			const yScale = d3.scaleLinear()
			.domain([0, d3.max(data, function(d) { return +d.value; })])
			.range([ height, 0 ]);
			
			const yaxis = d3.axisLeft()
			.ticks((data).length)
			.scale(yScale);
			
			const xaxis = d3.axisBottom()
			.ticks(data.length)
			.tickFormat(d3.timeFormat('%b %d'))
			.scale(xScale);
			
			
			svg.append("g")
			.attr("class", "axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xaxis);
			
			svg.append("g")
			.attr("class", "axis")
			.call(yaxis)
			
			const line = d3.line()
			.x(function(d) { return xScale(dateParser(d.date)); })
			.y(function(d) { return yScale(d.value); });
			
			const lines = svg.selectAll("lines")
			.data(data)
			.enter()
			.append("g");
			
			lines.append("path")
			.attr("d", function(d) { return line(data); })
			.attr("class", "pathline")
			
			const tooltip = svg.append("g")
			.style("pointer-events", "none");
			
			
			function pointermoved(event) {
				const i = d3.bisectCenter(data, xScale.invert(d3.pointer(event)[0]));
				tooltip.style("display", null);
				tooltip.attr("transform", `translate(${xScale(data[i].date)},${yScale(data[i].value)})`);
				
				const path = tooltip.selectAll("path")
				.data([,])
				.join(".pathline")
				.attr("fill", "white")
				.attr("stroke", "black");
				
				const text = tooltip.selectAll("text")
				.data([,])
				.join("text")
				.call(text => text
				.selectAll("tspan")
				.data(data[i])
				.join("tspan")
				.attr("x", 0)
				.attr("y", (_, i) => `${i * 1.1}em`)
				.attr("font-weight", (_, i) => i ? null : "bold")
				.text(d => d));
				
				const {x, y, width: w, height: h} = text.node().getBBox();
				console.log(y)
				text.attr("transform", `translate(${-w / 2},${15 - y})`);
    		path.attr("d", `M${-w / 2 - 10},5H-5l5,-5l5,5H${w / 2 + 10}v${h + 20}h-${w + 20}z`);
				svg.property("value", data[i]).dispatch("input", {bubbles: true});
			}
			
			function pointerleft() {
				tooltip.style("display", "none");
				svg.node().value = null;
				svg.dispatch("input", {bubbles: true});
			}
		},
	},
	mounted() {
		this.drawChart();
	}
	
}
</script>

<style>
.pathline {
	fill: none;
	stroke: #ed3700;
}
.axis text {
	fill: #2b2929;
	font-size: 120%;
}
</style>