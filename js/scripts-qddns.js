var randomColorFactor = function() {
	return Math.round(Math.random() * 255);
};
var randomColor = function(opacity) {
	return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
};

var config = {
	type: 'line',
	data: {
		labels: ["January", "March", "May", "July", "September", "November"],
		datasets: [{
			label: "Install",
			data: [0,1,2,3,4],
			fill: false,
			borderDash: [5, 5],
		}, {
			label: "Shortcode",
			data: [0,1,2,3,4],
			fill: false,
			borderDash: [5, 5],
		}, {
			label: "Widget",
			data: [4,3,2,1,0],
			lineTension: 0,
			fill: false,
		}, {
			label: "Service",
			data: [0,4,1,3,2],
			fill: false,
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
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Time'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Requests'
				}
			}]
		},
		title: {
			display: true,
			text: 'Recent Requests'
		}
	}
};

/*
$.each(config.data.datasets, function(i, dataset) {
	var background = randomColor(0.5);
	dataset.borderColor = background;
	dataset.backgroundColor = background;
	dataset.pointBorderColor = background;
	dataset.pointBackgroundColor = background;
	dataset.pointBorderWidth = 1;
});
*/
window.onload = function() {
	var ctx = document.getElementById("qddns-dashboard-canvas").getContext("2d");
	window.myLine = new Chart(ctx, config);
};
