<?php

if(is_admin()) {
	/* Dashboard widget */
	function qddns_dashboard_widget_function() {
		$ip = get_client_ip('', false);
		$host = ip_address_to_hostname( $ip );
		$num_users = get_num_valid_users();
		$total_requests = get_request_stats_table();
		$total_requests_count = count( $total_requests );
		$req_install_count = get_request_stats_table_count('install');
		$req_scode_count = get_request_stats_table_count('shortcode');
		$req_widget_count = get_request_stats_table_count('widget');
		$req_svc_count = get_request_stats_table_count('service');
		
		$monthData = array(
			'install_count' => $req_install_count,
			'scode_count' => $req_scode_count,
			'widget_count' => $req_widget_count,
			'svc_count' => $req_svc_count,
			'requests' => $total_requests
		);
		
		wp_enqueue_script( 'scripts-qddns-chartjs', plugins_url( 'js/Chart.bundle.min.js', __FILE__ ), array(), true);
		wp_enqueue_script( 'scripts-qddns-moment', plugins_url( 'js/moment.min.js', __FILE__ ), array(), true);
		wp_enqueue_script( 'scripts-qddns', plugins_url( 'js/scripts-qddns.js', __FILE__ ), array(), true);
		wp_localize_script( 'scripts-qddns', 'monthData', $monthData );

		// display information
		echo '<div style="display:table; width: 100%;">';
		echo '<div style="display:table-cell;"><big><strong>Your IP is ' . $ip . '</strong></big></div>';
		if($host != 'unknown')
			echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'qddns-address') . ' : ' . $host . ')</small></div>';
		echo '<div style="display:table-row;"><a href="admin.php?page=qddns">View Quick DynDNS Settings</a></div>';
		echo "</div>\n\n";
		echo '
		<div class="box-ip">
			<hr>
			This month, ' . $num_users . ' users have made ' . $total_requests_count . ' IP Lookup requests:
			<canvas id="qddns-dashboard-canvas-bar"></canvas>
			<canvas id="qddns-dashboard-canvas-line"></canvas>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<td>Source</td>
					<td>Requests</td>
				</tr>
				</thead>
				<tr>
					<td>Install</td>
					<td>' . $req_install_count . '</td>
				</tr>
				<tr>
					<td>Shortcode</td>
					<td>' . $req_scode_count . '</td>
				</tr>
				<tr>
					<td>Widget</td>
					<td>' . $req_widget_count . '</td>
				</tr>
				<tr>
					<td>Service</td>
					<td>' . $req_svc_count . '</td>
				</tr>
			</table>
			<hr>
			Like this plugin? <a href="http://jlong.co/donate">Donate</a> to support development!
		</div>';
	}

	function qddns_add_dashboard_widgets() {
		wp_add_dashboard_widget('qddns_dashboard_widget', __('Quick DynDNS Stats', 'qddns-address'), 'qddns_dashboard_widget_function');
	}

	add_action('wp_dashboard_setup', 'qddns_add_dashboard_widgets' );
}
?>
