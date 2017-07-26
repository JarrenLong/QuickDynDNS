<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//if(is_admin()) {
	/* Add links to the plugin page next to the Activate/Deactivate link */
	function qddns_plugin_action_links( $links ) {
		$new_links = array(
			'settings' => '<a href="options-general.php?page=qddns">Settings</a>',
			'faq' => '<a href="https://www.booksnbytes.net/quickdyndns#faq" target="_blank">FAQ</a>',
			'donate' => '<a href="https://www.booksnbytes.net/donate" target="_blank">Donate</a>'
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
					'support' => '<a href="https://www.booksnbytes.net/quickdyndns#support" target="_blank">Support</a>',
					'donate' => '<a href="https://www.booksnbytes.net/donate" target="_blank">Donate</a>'
				)
			);
		}
		
		return $links;
	}
	add_filter('plugin_row_meta', 'qddns_plugin_row_meta_links', 10, 2);
//}
?>
