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

var installs = new Array();
var shortcodes = new Array();
var widgets = new Array();
var services = new Array();

jQuery.each( monthData.requests , function(k, v) {
	switch(v.source) {
		case "install":
			installs.push( moment( v.time, 'YYYY-MM-DD HH:mm:ss' ) );
			break;
		case "shortcode":
			shortcodes.push( moment( v.time, 'YYYY-MM-DD HH:mm:ss' ) );
			break;
		case "widget":
			widgets.push( moment( v.time, 'YYYY-MM-DD HH:mm:ss' ) );
			break;
		case "service":
			services.push( moment( v.time, 'YYYY-MM-DD HH:mm:ss' ) );
			break;
		default:
		break;
	}
});

var config = {
	type: 'line',
	data: {
		labels: ["This Month", "Today"],
		datasets: [{
			label: "Install",
			data: installs,
			fill: false,
			backgroundColor: color1,
		}, {
			label: "Shortcode",
			data: shortcodes,
			fill: false,
			backgroundColor: color2,
		}, {
			label: "Widget",
			data: widgets,
			lineTension: 0,
			fill: false,
			backgroundColor: color3,
		}, {
			label: "Service",
			data: services,
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
				display: true,
				type: 'time',
                time: {
					unit: 'month',
					displayFormats: {
                        quarter: 'MMM D, hA'
                    }
                }
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
		data: [ parseInt( monthData.install_count ) ]
	}, {
		label: 'Widget',
		backgroundColor: color2,
		data: [ parseInt( monthData.widget_count ) ]
	}, {
		label: 'Shortcode',
		backgroundColor: color3,
		data: [ parseInt( monthData.scode_count ) ]
	}, {
		label: 'Service',
		backgroundColor: color4,
		data: [ parseInt( monthData.svc_count ) ]
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
