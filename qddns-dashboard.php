<?php

if(is_admin()) {
	/* Dashboard widget */
	function qddns_dashboard_widget_function() {
		// admin ip address
		$admin_ip_address = $_SERVER['REMOTE_ADDR'];
		if( !$admin_ip_address )
			$admin_ip_address = 'unknown';
		
		// admin hostname
		$admin_hostname = @gethostbyaddr( $admin_ip_address );
		if( !$admin_hostname || $admin_hostname == $admin_ip_address )
			$admin_hostname = 'Unknown';
		
		$num_users = 0;
		$total_requests = get_request_stats_table();
		
		$req_install = get_request_stats_table('install');
		$req_scode = get_request_stats_table('shortcode');
		$req_widget = get_request_stats_table('widget');
		$req_svc = get_request_stats_table('service');
		
		// display information
		echo '<div style="display:table; width: 100%;">';
		echo '<div style="display:table-cell;"><big><strong>Your IP is ' . $admin_ip_address . '</strong></big></div>';
		if($admin_hostname != 'unknown')
			echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'qddns-address') . ' : ' . $admin_hostname . ')</small></div>';
		echo "</div>\n\n";
		echo '
		<div class="box-ip">
			<hr>
			This month, ' . $num_users . ' users have made ' . $total_requests . ' IP Lookup requests:
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
			Like this plugin? <a href="http://jlong.co/donate">Make a donation</a> to keep it going!
		</div>';
	}

	function qddns_add_dashboard_widgets() {
		wp_add_dashboard_widget('qddns_dashboard_widget', __('Quick DynDNS Stats', 'qddns-address'), 'qddns_dashboard_widget_function');
	}

	add_action('wp_dashboard_setup', 'qddns_add_dashboard_widgets' );
}

?>
