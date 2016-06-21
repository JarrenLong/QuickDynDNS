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
var y_labels = new Array();
var shortcodes = new Array();
var widgets = new Array();
var services = new Array();

jQuery.each( monthData.requests , function(k, v) {
	y_labels.push( new Array( moment( v.time, 'YYYY-MM-DD HH:mm:ss' ), 1 ) );
	
	switch(v.source) {
		case "install":
			installs.push( 1 );
			shortcodes.push( 0 );
			widgets.push( 0 );
			services.push( 0 );
			break;
		case "shortcode":
			installs.push( 0 );
			shortcodes.push( 1 );
			widgets.push( 0 );
			services.push( 0 );
			break;
		case "widget":
			installs.push( 0 );
			shortcodes.push( 0 );
			widgets.push( 1 );
			services.push( 0 );
			break;
		case "service":
			installs.push( 0 );
			shortcodes.push( 0 );
			widgets.push( 0 );
			services.push( 1 );
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
