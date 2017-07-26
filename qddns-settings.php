<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// create custom plugin settings submenu and register settings
function qddns_plugin_settings() {
    //add_menu_page( 'Quick DynDNS Settings', 'Quick DynDNS', 'administrator', 'qddns', 'qddns_show_settings' );
	add_submenu_page( 'options-general.php', 'Quick DynDNS Settings', 'Quick DynDNS', 'administrator', __FILE__, 'qddns_show_settings' );
	add_action( 'admin_init', 'qddns_register_settings' );
}
add_action('admin_menu', 'qddns_plugin_settings');

function qddns_register_settings() {
	//register our settings
	register_setting( 'qddns-settings-group', 'qddns_enabled' );
	register_setting( 'qddns-settings-group', 'qddns_enable_user_auth' );
	register_setting( 'qddns-settings-group', 'qddns_show_powered_by_widget' );
}

function qddns_show_settings(){
?>
<div class="wrap">
	<h2>Quick DynDNS Settings</h2>
	<p>A simple plugin to show client IP address information anywhere on your site, and provide Dynamic DNS services for your users. Have any suggestions, feature requests, or find any bugs? <a href="https://www.booksnbytes.net/contact">Contact me</a> and I'll do my best to respond ASAP. Don't forget, <a href="https://www.booksnbytes.net/donate">making a donation</a> is a great way to get faster service ;)</p>
	<hr />
	<h3>Quickstart Instructions:</h3>
	<h4>To show user's their current IP address:</h4>
	<i>Add the [qddns] shortcode in your posts and pages</i>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;OR
	<br />
	<i>Drop the Quick DynDNS widget somewhere on your site</i>
	<hr />
	<p>Like this plugin? Make a donation to support future development!</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'qddns-settings-group' ); ?>
		<?php do_settings_sections( 'qddns-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">DDNS Services Enabled</th>
				<td><input type="checkbox" name="qddns_enabled" value="1" <?php checked( get_option('qddns_enabled', true) ); ?>" /></td>
			</tr>
<?php if( get_option( 'qddns_enabled' ) ) { ?>
			<tr valign="top">
				<th scope="row">Require users to authenticate</th>
				<td><input type="checkbox" name="qddns_enable_user_auth" value="1" <?php checked( get_option('qddns_enable_user_auth', true) ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Show 'Powered By' text in widget</th>
				<td><input type="checkbox" name="qddns_show_powered_by_widget" value="1" <?php checked( get_option('qddns_show_powered_by_widget', true) ); ?>" /></td>
			</tr>
<?php } ?>
		</table>
		
		<?php submit_button(); ?>
	</form>
</div>
<?php
}

?>
