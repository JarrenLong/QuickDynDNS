<?php

//if(is_admin()) {
	/* Add links to the plugin page next to the Activate/Deactivate link */
	function qddns_plugin_action_links( $links ) {
		$new_links = array(
			'settings' => '<a href="options-general.php?page=qddns">Settings</a>',
			'faq' => '<a href="http://jlong.co/quickdyndns#faq" target="_blank">FAQ</a>',
			'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>'
		);
		
		return array_merge( $links, $new_links );
	}
	
	$plugin = plugin_name();
	add_filter("plugin_action_links_$plugin", 'qddns_plugin_action_links' );
	
	function qddns_plugin_row_meta_links($links, $file) {
		$plugin_file = plugin_name();
		
		if ( $file == $plugin_file ) {
			return array_merge(
				$links, array(
					'support' => '<a href="http://jlong.co/quickdyndns#support" target="_blank">Support</a>',
					'donate' => '<a href="http://jlong.co/donate" target="_blank">Donate</a>'
				)
			);
		}
		
		return $links;
	}
	add_filter('plugin_row_meta', 'qddns_plugin_row_meta_links', 10, 2);
//}
?>
