

//Sales Activity
function statistics2() {
	setTimeout(()=>{
		var options = {
			series: [{
			name: "Sales",
			data: [32, 15, 63, 51, 136, 62, 99, 42, 178, 76, 32, 180]
		}],
			chart: {
			height: 280,
			type: 'line',
			zoom: {
			enabled: false
			},
			dropShadow: {
				enabled: true,
				enabledOnSeries: undefined,
				top: 5,
				left: 0,
				blur: 3,
				color: '#000',
				opacity: 0.1
			},
		},
		dataLabels: {
			enabled: false
		},
		legend: {
			position: "top",
			horizontalAlign: "left",
			offsetX: -15,
			fontWeight: "bold",
		},
		stroke: {
			curve: 'smooth',
			width: '3'
		},
		grid: {
			borderColor: '#f2f6f7',
		},
		colors: [ myVarVal || "#1fc5db"],
		yaxis: {
			title: {
			text: 'Growth',
				style: {
					color: '#adb5be',
					fontSize: '14px',
					fontFamily: 'poppins, sans-serif',
					fontWeight: 600,
					cssClass: 'apexcharts-yaxis-label',
				},
			},
			labels: {
			formatter: function (y) {
				return y.toFixed(0) + "";
			}
			}
		},
		xaxis: {
			type: 'number',
			categories: ['1','2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
			axisBorder: {
						show: true,
						color: 'rgba(119, 119, 142, 0.05)',
						offsetX: 0,
						offsetY: 0,
					},
					axisTicks: {
						show: true,
						borderType: 'solid',
						color: 'rgba(119, 119, 142, 0.05)',
						width: 6,
						offsetX: 0,
						offsetY: 0
					},
			labels: {
			rotate: -90
			}
		}
		};
		document.getElementById('statistics2').innerHTML = ''
		var chart = new ApexCharts(document.querySelector("#statistics2"), options);
		chart.render();
	}, 300);	
}


// Budget Chart
function budget() {
	setTimeout(()=>{
		var options = {
			series: [{
			name: 'This Week',
			data: [44, 42, 57, 86, 58, 55, 70],
			},{
				name: 'Last Week',
			data: [-34, -22, -37, -56, -21, -35, -60],
		}],
			chart: {
			stacked: true,
			type: 'bar',
			height: 250,
		},
		grid: {
				borderColor: '#f2f6f7',
			},
		colors: [ myVarVal || "#38cab3","#e4e7ed"],
		plotOptions: {
			bar: {
			endingShape: 'rounded',
			colors: {
				ranges: [{
				from: -100,
				to: -46,
				color: '#ebeff5'
				}, {
				from: -45,
				to: 0,
				color: '#ebeff5'
				}]
			},
			columnWidth: '25%',
			}
		},
		dataLabels: {
			enabled: false,
		},
		legend: {
			show: true,
			position:'top',
		},
		yaxis: {
			title: {
				style: {
					color: '#adb5be',
					fontSize: '14px',
					fontFamily: 'poppins, sans-serif',
					fontWeight: 600,
					cssClass: 'apexcharts-yaxis-label',
				},
			},
			labels: {
			formatter: function (y) {
				return y.toFixed(0) + "";
			}
			}
		},
		xaxis: {
			type: 'day',
			categories: ['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'sat'],
			axisBorder: {
						show: true,
						color: 'rgba(119, 119, 142, 0.05)',
						offsetX: 0,
						offsetY: 0,
					},
					axisTicks: {
						show: true,
						borderType: 'solid',
						color: 'rgba(119, 119, 142, 0.05)',
						width: 6,
						offsetX: 0,
						offsetY: 0
					},
			labels: {
			rotate: -90
			}
		}
		};
		document.getElementById('budget').innerHTML = ''
		var chart = new ApexCharts(document.querySelector("#budget"), options);	
		chart.render();
	}, 300);	
}


//Visitors chart
 function viewers1() {
	setTimeout(()=>{
		var options = {
			series: [{
			name: 'Male',
			data: [51, 44, 55, 42, 58,50, 62],
			},{
			name: 'Female',
			data: [56, 58, 38, 50, 64,45, 55]
		}],
			chart: {
			height: 270,
			type: 'line',
			toolbar: {
				show: false,
			},
			background: 'none',
			fill:"#fff",
		},
		grid: {
			borderColor: '#f2f6f7',
		},
		colors: [ myVarVal || "#1fc5db", "#e4e7ed"],
		background: 'transparent',
		dataLabels: {
			enabled: false
		},
		stroke: {
			curve: 'straight',
			width:2
		},
		xaxis: {
			type: 'day',
			categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
		},
		dataLabels: {
			enabled: false,
		},
		legend: {
			show: true,
			position:'top',
		},
		xaxis: {
			show:false,
			axisBorder: {
						show: false,
						color: 'rgba(119, 119, 142, 0.05)',
						offsetX: 0,
						offsetY: 0,
					},
					axisTicks: {
						show: false,
						borderType: 'solid',
						color: 'rgba(119, 119, 142, 0.05)',
						width: 6,
						offsetX: 0,
						offsetY: 0
					},
			labels: {
			rotate: -90,
			}
		},
		yaxis: {
			show:false,
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false,
			}
		},
		tooltip: {
			x: {
			format: 'dd/MM/yy HH:mm'
			},
		},
		};
		document.getElementById('Viewers1').innerHTML = ''
		var chart = new ApexCharts(document.querySelector("#Viewers1"), options);
		chart.render();
	}, 300);	
}

$(function() {
	
	$('#example2').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
	});
	
});