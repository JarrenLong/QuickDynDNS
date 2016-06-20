var randomColorFactor = function() {
	return Math.round(Math.random() * 255);
};
var randomColor = function(opacity) {
	return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
};
var randomScalingFactor = function() {
	return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
};

var color1 = randomColor(0.5);
var color2 = randomColor(0.5);
var color3 = randomColor(0.5);
var color4 = randomColor(0.5);

var config = {
	type: 'line',
	data: {
		labels: ["This Month", "Today"],
		datasets: [{
			label: "Install",
			data: [0,1,2,3,4],
			fill: false,
			backgroundColor: color1,
		}, {
			label: "Shortcode",
			data: [4,0,3,1,2],
			fill: false,
			backgroundColor: color2,
		}, {
			label: "Widget",
			data: [4,3,2,1,0],
			lineTension: 0,
			fill: false,
			backgroundColor: color3,
		}, {
			label: "Service",
			data: [0,4,1,3,2],
			fill: false,
			backgroundColor: color4,
		}]
	},
	options: {
		responsive: true,
		legend: {
			position: 'top',
		},
		hover: { mode: 'label' },
		scales: {
			xAxes: [{
				display: true
			}],
			yAxes: [{
				display: true
			}]
		},
		title: {
			display: true,
			text: 'Requests This Month'
		}
	}
};


var barChartData = {
	labels: ["This Month"],
	datasets: [{
		label: 'Install',
		backgroundColor: color1,
		data: [1]
	}, {
		label: 'Widget',
		backgroundColor: color2,
		data: [19]
	}, {
		label: 'Shortcode',
		backgroundColor: color3,
		data: [46]
	}, {
		label: 'Service',
		backgroundColor: color4,
		data: [85]
	}]

};

window.onload = function() {
	var ctx = document.getElementById("qddns-dashboard-canvas-line").getContext("2d");
	window.myLine = new Chart(ctx, config);
	
	var ctx2 = document.getElementById("qddns-dashboard-canvas-bar").getContext("2d");
	window.myBar = new Chart(ctx2, {
		type: 'bar',
		data: barChartData,
		options: {
			title:{
				display:true,
				text:"Requests This Month By Source"
			},
			tooltips: {
				mode: 'label'
			},
			responsive: true,
			scales: {
				xAxes: [{
					stacked: true
				}],
				yAxes: [{
					stacked: true
				}]
			}
		}
	});
};
