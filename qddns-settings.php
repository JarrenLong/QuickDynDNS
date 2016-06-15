<?php

/* Plugin Settings Page */
function qddns_show_settings(){
	echo '
	<h1>Show IP Address Information</h1> <div>A simple plugin to show your IP address information on any of your pages, posts or widgets. <br>Shows your IP address on your dashboard from any location.</div> <hr> <div>The idea is simple, give it a go, let us know what you think of this plugin. Any suggested updates <br> I\'ll consider in each build <a href="http://jlong.co/contact">click here</a> to contact me anytime.</div>
	<h2>Use on pages, widgets or posts</h2> <hr> <div>Let\'s say a user lands on your page or post, and you want to show them there IP address. <br> <h3>You could use</h3> What\'s my IP address: [qddns]</div> <br>
	<strong>Q)</strong>. What\'s the point in showing a person there IP address? <br> <strong>A)</strong>. You may want to create a secure page are on your website and show the user their IP address.<hr>
	';
}

function qddns_plugin_settings() {
    add_menu_page('Quick DynDNS Settings', 'Quick DynDNS', 'administrator', 'qddns', 'qddns_show_settings');
}

add_action('admin_menu', 'qddns_plugin_settings');

?>
