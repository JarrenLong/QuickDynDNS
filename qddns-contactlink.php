<?php

if(is_admin()) {
	/* Add a Contact link to the plugin page */
	function user_ip_contact_link( $links ) {
		$contact_link = '<a href="http://jlong.co/contact" target="_blank">Contact Developer</a>';
		$new_links = array(
			'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>',
			'settings' => '<a href="options-general.php?page=qddns">Settings</a>',
			'faq' => '<a href="http://jlong.co/qddns#faq" target="_blank">FAQ</a>',
			'support' => '<a href="http://jlong.co/qddns#support" target="_blank">Support</a>'
		);
		
		$links = array_merge( $links, $new_links );
		
		array_push( $links, $contact_link );
		
		return $links;
	}
	
	$plugin = plugin_basename( __FILE__ ); 
	add_filter("plugin_action_links_$plugin", 'user_ip_contact_link' );
}
