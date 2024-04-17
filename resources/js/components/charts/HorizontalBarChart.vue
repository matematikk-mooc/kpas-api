<template>
	<span id="diagramContainer"></span>
</template>

<script>
import * as d3 from "d3";

export default{
	name: 'HorizontalBarChart',
	props: {
		data: Array
	},

	mounted() {
		this.drawDiagram();
	},
	watch: {
		data(){
			d3.selectAll("table").remove()
			this.drawDiagram()
		}
	},
	methods: {
		drawDiagram() {
			let diagramData = this.data;

			const MAX_X = 1;

			// These should match CSS
			const SMALL_BREAKPOINT = 768;
			const PREFFERED_COLUMN_WIDTH = {
				LARGE: 300,
				SMALL: 300
			};

			const container = d3.select("#diagramContainer");
			const workingWidth = 800;
			// Column 1 (name column) is given a set width
			const column1width = window.innerWidth > SMALL_BREAKPOINT ? PREFFERED_COLUMN_WIDTH.LARGE : PREFFERED_COLUMN_WIDTH.SMALL;
			// Rest of width is given to column 2
			const column2width = workingWidth - column1width;

			const currentSort = {};

			const table = container.append("table")
			.attr("class", "table-completed")
			.attr("width", workingWidth)
			.attr("role", "table");

			const headers = [
			{
				name: "Tittel",
				sortDirection: "none",
				sortField: "position",
				id: "byKey",
				colspan: 1
			},
			{
				name: "Antall fullført",
				sortDirection: "none",
				sortField: "count",
				id: "byValue",
				colspan: MAX_X
			}
			];

			if (currentSort.sortDirection && currentSort.sortField) {
				const previouslySorted = headers[1].currentSort.sortField;
				if (previouslySorted) {
					previouslySorted.sortDirection = currentSort.sortDirection;
				}
			}

			const thead = table.append("thead");
			// Names of columns
			const tableHeader = thead.append("tr")
			.attr("role", "row")
			.attr("class", "table-header");
			// To use aria-sort we need to add role and scope for html validation
			const th = tableHeader.selectAll("tr")
			.data(headers)
			.enter()
			.append("th")
			.attr("class", "table-sort")
			.attr("colspan", d => d.colspan)
			.attr("role", "columnheader")
			.attr("scope", "col")
			.attr("aria-sort", d => d.sortDirection);

			window.requestAnimationFrame(() => {
        		table.style('--table-header-height', tableHeader.offsetHeight);
    		});

			// When sorting, get the needed translate amount to get from old position to new
			const getTranslatePosition = (d, el) => {
				const newIndex = diagramData.map(_d => _d.title).indexOf(d.title);

				const newOffsetTop = diagramData.reduce((acc, _d, i) => {
					if (i < newIndex) {
						acc += _d.rowHeight;
					}
					return acc;
				}, 0);
				const currentOffsetTop = el.getBoundingClientRect().top - el.parentElement.getBoundingClientRect().top;
				const translateAmount = newOffsetTop - currentOffsetTop;
				return translateAmount;
			};

			const sortRows = async () => {
				const rows = table.selectAll("tbody tr");
				await rows.transition()
				.duration(500)
				.style("border-color", "transparent")
				.style("transform", function (d) {
					return `translate3d(0,${getTranslatePosition(d, this)}px,0)`;
				})
				.end();

				window.requestAnimationFrame(function () {
					rows.style("transform", "translate3d(0,0px,0)")
					.style("border-color", null)
					.data(diagramData, d => d.title)
					.order();
				});
			};

			const handleSortClick = (headerEl, sortField) => {
				const header = d3.select(headerEl);
				const sort = header.attr("aria-sort");
				th.attr("aria-sort", "none");

				header.attr("aria-sort", () => {
					if (sort === "none" || sort === "ascending") return "descending";
					return "ascending";
				});

				// Save state for reuse when redrawing chart after resize
				currentSort.sortDirection = header.attr("aria-sort");
				currentSort.sortField = sortField;

				// Use d3.ascending / d3.descending function with array.sort to sort data array
				diagramData = diagramData.slice().sort((a, b) => d3[header.attr("aria-sort")](a[sortField], b[sortField]));
				sortRows();
			};

			th.append("button")
			.attr("class", "column-sorter")
			.attr("id", d => d.id)
			.text(d => d.name)
			.on("click", function (event, d) {
				handleSortClick(this.parentNode, d.sortField);
			});

			/* ---------------------- */
			/* -------- BODY -------- */

			const tbody = table.append("tbody");
			const tr = tbody.selectAll("tr")
			.data(diagramData, d => d.title)
			.enter()
			.append("tr")
			.attr("style", d => `transform: translate3d(0,0px,0)`);


			// Create the name column
			tr.append("td").attr("class", "data name")
			.attr("width", column1width)
			.text(function (d) {
				return d.title;
			});

			// Create the percent value column
			tr.append("td").attr("class", "data value")
			.append("div")
			.attr("class", "bar")
			.text(function(d) {
				return d.count;
			})
			.attr("style", function(d) {
				var max_value = Math.max(...diagramData.map(o => o.count))
				if (max_value < 5){
					max_value = 10
				}
				var barWidth = d.count / max_value * 100;
				return "width:" + barWidth + "%";
			})

			// Add row height to each row object
			diagramData = diagramData.map((d) => {
				d.rowHeight = 34;
				return d;
			});

		}
	}
}
</script>

<style>

.table-completed *, .table-completed ::after, .table-completed ::before {
	box-sizing: border-box;
}

.table-completed {
	box-sizing: border-box;
	font-family: "Roboto", sans-serif;
	font-size: 14px;
	border-spacing: 2px;
	border-collapse: collapse;
}

@media (max-width: 767px) {
	.table-completed {
		font-size: 12px;
	}

	.form-inline {
		flex-direction: column;
		align-items: stretch;
	}
}

.table-completed th {
	font-weight: normal;
	position: sticky;
	top: 0;
	z-index: 1;
}

.table-header th {
	background: #BAC6D8;
	-webkit-print-color-adjust: exact !important;
	print-color-adjust: exact !important;
	position: -webkit-sticky;
}

.table-header th:first-child {
	text-align: left;
}

.table-ticks th {
	text-align: center;
	background: white;
	top: var(--table-header-height);
}

.table-completed td.name, .column-sorter {
	padding: 2px
}

.table-completed td.value {
	background-image: linear-gradient(to right, rgba(0,0,0,0.1) 1px, transparent 1px);
	background-size: 142px 100%;
	padding: 6px 0 6px 0;
	margin: 4px 0 4px 0;
}

.table-completed .bar {
	background: #7DBF9D;
	text-indent: 4px;
	text-align: left center;
	height: 36px;
	transition: 0.2s ease-out background;
	min-width: 3px;
	-webkit-print-color-adjust: exact !important;   /* Chrome, Safari 6 – 15.3, Edge */
	print-color-adjust: exact !important;
}

.table-completed .bar:hover{
	background: #9DBF9D;
}

/* Sort buttons and arrow indicators */
.column-sorter {
	height: 100%;
	text-align: left;
	font-size: 1rem;
	background: transparent;
	border: none;
	padding-right: 20px;
	font-weight: bold;
	position: relative;
}

.column-sorter:before, .column-sorter:after {
	border: 4px solid transparent;
	content: "";
	display: block;
	height: 0;
	right: 5px;
	top: 50%;
	position: absolute;
	width: 0;
}

.column-sorter:before {
	border-top-color: currentColor;
	margin-top: 1px;
}

.column-sorter:after {
	border-bottom-color: currentColor;
	margin-top: -9px;
}

.table-sort[aria-sort="descending"] .column-sorter:after {
	border-bottom-color: transparent;
}

.table-sort[aria-sort="ascending"] .column-sorter:before {
	border-top-color: transparent;
}

.form-inline {
	display: flex;
	flex-flow: row wrap;
	align-items: center;
}

.form-inline label {
	margin: 5px 10px 5px 0;
}

.form-inline input {
	vertical-align: middle;
	margin: 5px 10px 5px 0;
	padding: 10px;
	background-color: #fff;
	border: 1px solid #ddd;
}

.form-inline button {
	padding: 10px 20px;
	background-color: #7DBF9D;
	border: 1px solid #ddd;
	color: white;
	cursor: pointer;
}

.form-inline button:hover {
	background-color: #7DBF9D;
}

</style>
