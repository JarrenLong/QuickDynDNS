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
	<p>Like this plugin? <a href="https://www.booksnbytes.net/donate">Make a donation to support future development!</a></p>
	
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
