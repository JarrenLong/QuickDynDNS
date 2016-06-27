var color1 = '#2F4372';
var color2 = '#76296C';
var color3 = '#8DA339';
var color4 = '#AA853B';

var y_labels = new Array();
var installs = new Array();
var shortcodes = new Array();
var widgets = new Array();
var services = new Array();

jQuery.each( monthData.daily_requests , function(k, v) {
	y_labels.push( new Array( moment( v.day, 'YYYY-MM-DD' ), 1 ) );
	
	switch(v.source) {
		case "install":
			installs.push( v.requests );
			break;
		case "shortcode":
			shortcodes.push( v.requests );
			break;
		case "widget":
			widgets.push( v.requests );
			break;
		case "service":
			services.push( v.requests );
			break;
		default:
		break;
	}
});

var config = {
	type: 'line',
	data: {
		labels: y_labels,
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
				display: false
			}],
			yAxes: [{
				display: false
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
