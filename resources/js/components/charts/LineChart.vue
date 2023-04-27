<template>
	<div id="linechart"></div>
	<p v-if="noDateCompleted != 0">Antall fullført uten tilgjengelig dato: {{ noDateCompleted }}</p>
</template>

<script>
import * as d3 from "d3";
export default {
	name: "LineChart",

	props: {
		data: [],
	},
	data() {
		return {
			tooltip: null,
			chartData: [],
			noDateCompleted: 0
		}
	},
	methods: {
		drawChart() {
			this.mapData()
			const data = this.chartData

			let margin = {top: 20, right: 25, bottom: 125, left: 25},
			width = 800 - margin.left - margin.right,
			height = 600 - margin.top - margin.bottom


			const svg = d3.select("#linechart")
			.append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", `translate(${margin.left},${margin.top})`);

			var dateParser = d3.timeParse("%Y-%m-%d");

			const xScale = d3.scaleTime()
			.domain(d3.extent(data, function(d) { return dateParser(d.date)}))
			.range([ 0, width ]);


			const yScale = d3.scaleLinear()
			.domain([0, d3.sum(data, function(d) { return +d.value; })])
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

			let cur = 0

			const line2 = d3.line()
			.x(function(d) { return xScale(dateParser(d.date)); })
			.y(function(d) { cur = cur + d.value; return yScale(cur); });

			const lines = svg.selectAll("lines")
			.data(data)
			.enter()
			.append("g");

			lines.append("path")
			.attr("d", function(d) { return line(data); })
			.attr("class", "pathline")

			lines.append("path")
			.attr("d", function(d) { return line2(data); })
			.attr("class", "pathline2")


			const tooltip = svg.append("g")
			.append("text")
			.attr("x", 9)
			.attr("dy", ".35em")
			.style("font-size", "12px")
			.style("background-color", "white");

			svg
			.append("rect")
			.attr("class", "overlay")
			.attr("x", margin.left)
			.attr("y", margin.top)
			.attr("width", width)
			.attr("height", height)
			.on("mouseover", () => tooltip.style("display", null))
			.on("mouseout", () => tooltip.style("display", "none"))
			.on("mousemove", mousemove);
			function mousemove(event) {
				const x = xScale.invert(d3.pointer(event)[0]);
				const unixTimestamps = data.map(d => new Date(d.date).getTime());
				const i = d3.bisect(unixTimestamps, x.getTime() );
				const d = data[i-1];
				tooltip.attr("transform", `translate(${xScale(d.date)},${yScale(d.value)})`);
				tooltip.attr("x", xScale(d.date))
				tooltip.attr("y", yScale(d.value))
				const partial = data.slice(0, i+1)
				const sum = d3.sum(partial, function(d) { return +d.value; })
				tooltip.text(() => {
					return "Dato: " + d.date + "\n Antall i dag: " + d.value + "\n Antall til nå: " + sum;
				});
				tooltip.attr("text-anchor", "start");
			}

		},
		mapData(){
			this.chartData = []
			this.noDateCompleted = 0
			for(const item of this.data){
				if(item[0] == null){
					this.noDateCompleted = item[1]
				}
				else {
					this.chartData.push({"date" : item[0], "value": item[1]})
				}
			}
		}
	},
	watch: {
		data(){
			const parent = document.getElementById("linechart")
			if(parent.hasChildNodes()){
				parent.removeChild(parent.children[0])
			}
			this.drawChart()
		}
	}
}
</script>

<style>
.pathline {
	fill: none;
	stroke: #ed3700;
}

.pathline2 {
	fill: none;
	stroke: green;
}

.axis text {
	fill: #2b2929;
	font-size: 120%;
}

.overlay {
	fill: none;
	pointer-events: all;
}

</style>