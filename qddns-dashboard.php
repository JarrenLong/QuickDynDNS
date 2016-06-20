<?php

if(is_admin()) {
	/* Dashboard widget */
	function qddns_dashboard_widget_function() {
		$ip = get_client_ip('', false);
		$host = ip_address_to_hostname( $ip );
		$num_users = get_num_valid_users();
		$total_requests = get_request_stats_table();
		$req_install = get_request_stats_table('install');
		$req_scode = get_request_stats_table('shortcode');
		$req_widget = get_request_stats_table('widget');
		$req_svc = get_request_stats_table('service');
		
		// display information
		echo '<div style="display:table; width: 100%;">';
		echo '<div style="display:table-cell;"><big><strong>Your IP is ' . $ip . '</strong></big></div>';
		if($host != 'unknown')
			echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'qddns-address') . ' : ' . $host . ')</small></div>';
		echo '<div style="display:table-row;"><a href="admin.php?page=qddns">View Quick DynDNS Settings</a></div>';
		echo "</div>\n\n";
		echo '
		<script src="../wp-content/plugins/quickdyndns/css/chartjs/dist/Chart.bundle.js"></script>
		<div class="box-ip">
			<hr>
			This month, ' . $num_users . ' users have made ' . $total_requests . ' IP Lookup requests:
			<canvas id="qddns-dashboard-canvas"></canvas>
			<script src="../wp-content/plugins/quickdyndns/scripts-qddns.js"></script>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<td>Source</td>
					<td>Requests</td>
				</tr>
				</thead>
				<tr>
					<td>Install</td>
					<td>' . $req_install . '</td>
				</tr>
				<tr>
					<td>Shortcode</td>
					<td>' . $req_scode . '</td>
				</tr>
				<tr>
					<td>Widget</td>
					<td>' . $req_widget . '</td>
				</tr>
				<tr>
					<td>Service</td>
					<td>' . $req_svc . '</td>
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
