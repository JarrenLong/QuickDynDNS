<?php

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
	
	// display information
	echo '<div style="display:table; width: 100%;">';
	echo '<div style="display:table-cell;"><big><strong>' . $admin_ip_address . '</strong></big></div>';
	if($admin_hostname != 'unknown')
		echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'qddns-address') . ' : ' . $admin_hostname . ')</small></div>';
	echo "</div>\n\n";
	echo '<div class="box-ip"><hr> Your IP address is something you might rarely think about, it\'s important to know what your IP address is and when it changes. Your IP address is used to identify computers on the Internet. <hr> Want to know more about IP addresses <a href="https://en.wikipedia.org/wiki/IP_address">click here</a> - Wiki Reference.  <hr>Show IP address, version 1.3</div>';
}

function qddns_add_dashboard_widgets() {
	wp_add_dashboard_widget('qddns_dashboard_widget', __('Your IP Address & Hostname', 'qddns-address'), 'qddns_dashboard_widget_function');
}

add_action('wp_dashboard_setup', 'qddns_add_dashboard_widgets' );

?>
