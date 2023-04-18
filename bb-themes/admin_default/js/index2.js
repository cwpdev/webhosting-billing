function statistics3() {
	setTimeout(()=>{
		var options = {
			series: [{
			name: 'active',
			data: [44, 42, 57, 86, 58, 55, 70, 43, 23, 54, 77, 34],
			},{
				name: 'inactive',
			data: [-34, -22, -37, -56, -21, -35, -60, -34, -56, -78, -89,-53],
		}],
			chart: {
			stacked: true,
			type: 'bar',
			height: 350,
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
			text: 'Growth',
				style: {
					color: '	#adb5be',
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
			type: 'month',
			categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
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
		document.getElementById('statistics3').innerHTML = ''
		var chart = new ApexCharts(document.querySelector("#statistics3"), options);	
		chart.render();
	}, 300);	
}	

//Visitors chart
function viewers2() {
	setTimeout(()=>{
		var options = {
			series: [{
			name: 'Male',
			data: [44, 42, 57, 86, 58, 55, 70],
			color:['#766df9']
			},{
				name: 'Female',
				data: [34, 22, 47, 56, 21, 35, 60],
				color:['#ebeff5']}
			],
			chart: {
			type: 'bar',
			stacked: true,
			height: 330
		},
		grid: {
			borderColor: '#eff2f6',
		},
		colors: [ myVarVal || "#38cab3","#e4e7ed"],
		plotOptions: {
			bar: {
			horizontal: false,
			columnWidth: '30%',
			},
		},
		
		dataLabels: {
			enabled: false
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent']
		},
		states: {
			hover: {
			filter: {
				type: 'none'
			}
			}
		},yaxis: {
			title: {
				style: {
					color: '	#adb5be',
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
			categories: ['Mon','Tue', 'Web', 'Thu', 'Fri', 'Sat', 'Sun'],
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
		},
		fill: {
			opacity: 1
		},
		legend: {
			position: "top"	
			},
		};
			document.getElementById('Viewers2').innerHTML = ''
		var chart = new ApexCharts(document.querySelector("#Viewers2"), options);
		chart.render();
	}, 300);	
}	  

$(function() {
	
	$('#example1').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
	});
	
});