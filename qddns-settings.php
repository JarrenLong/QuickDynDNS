<?php

/* Plugin Settings Page */
function qddns_show_settings(){
	echo '
<h1>Quick DynDNS Settings</h1>
<div>
	A simple plugin to show client IP address information anywhere on your site, and provide Dynamic DNS services for your users. Have any suggestions, feature requests, or find any bugs? <a href="http://jlong.co/contact">Contact me</a> and I\'ll do my best to respond ASAP. Don\'t forget, <a href="http://jlong.co/donate">making a donation</a> is a great way to get faster service ;)
</div>
<hr />
<h2>Quickstart Instructions:</h2>
<div>
<h3>To show user\'s their current IP address:</h3>
<i>Add the [qddns] shortcode in your posts and pages</i>
<br />
&nbsp;&nbsp;&nbsp;&nbsp;OR
<br />
<i>Drop the Quick DynDNS widget somewhere on your site</i>
<hr />
	';
}

function qddns_plugin_settings() {
    add_menu_page('Quick DynDNS Settings', 'Quick DynDNS', 'administrator', 'qddns', 'qddns_show_settings');
}
add_action('admin_menu', 'qddns_plugin_settings');

function qddns_plugin_links($links, $file) {
	if ( strpos( $file, 'quickdyndns.php' ) !== false ) {
		$new_links = array(
			'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>',
			'settings' => '<a href="options-general.php?page=qddns">Settings</a>',
			'faq' => '<a href="http://jlong.co/qddns#faq" target="_blank">FAQ</a>',
			'support' => '<a href="http://jlong.co/qddns#support" target="_blank">Support</a>'
		);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;

}
//add_filter('plugin_row_meta', 'qddns_plugin_links');
