<template>
	<div id="linechart"></div>
</template>

<script>
import { select, scaleTime, scaleLinear, axisLeft, axisBottom, line as d3Line, extent, sum, timeParse, timeFormat, bisect } from "d3";
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
	mounted() {
    this.drawChart();
  },
	methods: {
		drawChart() {
			this.mapData()
			const data = this.chartData

			let margin = {top: 20, right: 25, bottom: 125, left: 25},
			width = 800 - margin.left - margin.right,
			height = 600 - margin.top - margin.bottom


			const svg = select("#linechart")
			.append("svg")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", `translate(${margin.left},${margin.top})`);

			var dateParser = timeParse("%Y-%m-%d");

			const xScale = scaleTime()
			.domain(extent(data, function(d) { return dateParser(d.date)}))
			.range([ 0, width ]);


			const yScale = scaleLinear()
			.domain([0, sum(data, function(d) { return +d.value; })])
			.range([ height, 0 ]);

			const yaxis = axisLeft()
			.ticks((data).length > 15 ? 15 : (data).length)
			.scale(yScale);

			const xaxis = axisBottom()
			.ticks(data.length >10? 10 : data.length)
			.tickFormat(timeFormat('%b %d'))
			.scale(xScale);


			svg.append("g")
			.attr("class", "axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xaxis);

			svg.append("g")
			.attr("class", "axis")
			.call(yaxis)

			//Set values for lines per date and total
			const line = d3Line()
			.x(function(d) { return xScale(dateParser(d.date)); })
			.y(function(d) { return yScale(d.value); });

			let cur = 0

			const line2 = d3Line()
			.x(function(d) { return xScale(dateParser(d.date)); })
			.y(function(d) { cur = cur + d.value; return yScale(cur); });

			const lines = svg.selectAll("lines")
			.data(data)
			.enter()
			.append("g");

			//Draw paths for per date and total
			lines.append("path")
			.attr("d", function(d) { return line(data); })
			.attr("class", "pathline1")

			lines.append("path")
			.attr("d", function(d) { return line2(data); })
			.attr("class", "pathline2")


			//Adding legend
			const legend1 = svg.append("g")
			.attr("class", "legend");
			legend1.append("rect")
			.attr("class", "legend-line pathline1")
			.attr("width", 30)
			.attr("height", 2)
			.attr("y", 600 - 75);
			legend1.append("text")
			.text("Fullført per dag")
			.attr("y", 600 - 70)
			.attr("x", 35);

			const legend2 = svg.append("g")
			.attr("class", "legend");
			legend2.append("rect")
			.attr("class", "legend-line pathline2")
			.attr("width", 30)
			.attr("height", 2)
			.attr("y", 600 - 55);
			legend2.append("text")
			.text("Fullført (akkumulert)")
			.attr("y", 600 - 50)
			.attr("x", 35);

			//Adding tooltip
			const tooltip = svg.append("g")
			.append("text")
			.style("font-size", "12px")

			svg
			.append("rect")
			.attr("class", "overlay")
			.attr("width", width + 60)
			.attr("height", height + 60)
			.on("mouseover", () => tooltip.style("display", null))
			.on("mouseout", () => tooltip.style("display", "none"))
			.on("mousemove", mousemove);

			function mousemove(event) {
				const x = xScale.invert(pointer(event)[0]);
				const unixTimestamps = data.map(d => new Date(d.date).getTime());
				const i = bisect(unixTimestamps, x.getTime() );
				const d = data[i-1];
				const [x1, y] = pointer(event);
        tooltip
        .attr('transform', `translate(${x1-100}, ${y-50})`);
				const partial = data.slice(0, i+1)
				const sum = sum(partial, function(d) { return +d.value; })
				tooltip.html(() => {
					return `<tspan x="0" dy="1.2em"><tspan style="font-weight: bold">Dato:</tspan> ${d.date}</tspan>` +
					`<tspan x="0" dy="1.2em"><tspan style="font-weight: bold">Antall i dag:</tspan> ${d.value}</tspan>` +
					`<tspan x="0" dy="1.2em"><tspan style="font-weight: bold">Antall til nå:</tspan> ${sum}</tspan>`;
				});
				tooltip.style("outline", "thin solid black")
				.style("fill", "black")
				.attr("text-anchor", "start");
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
.pathline1 {
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