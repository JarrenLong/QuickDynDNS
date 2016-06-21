<?php
/*
 * Plugin name: Quick DynDNS
 * Plugin URI: http://jlong.co
 * Description: Quick DynDNS provides free and premium Dynamic DNS services for your members, allowing them to know their current IP address wherever they are.
 * Version: 1.0.42
 * Author: Jarren Long
 * Author URI: http://jlong.co
 * License: GPLv3
 */
/*
Copyright 2016 Jarren Long (jarrenlong@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

global $qddns_db_version;
$qddns_db_version = '1.0.42';

/* Plugin JS and CSS */
wp_enqueue_style('styles-qddns', plugins_url( 'css/style-qddns.css', __FILE__ ), array(), 'all');
//wp_enqueue_script('scripts-qddns-chartjs', plugins_url( 'js/Chart.bundle.min.js', __FILE__ ), array(), true);
//wp_enqueue_script('scripts-qddns', plugins_url( 'js/scripts-qddns.js', __FILE__ ), array(), true);

function plugin_name() {
	return plugin_basename( __FILE__ ); 
}

include('qddns-rewrite.php');
include('qddns-core.php');
include('qddns-install.php');
include('qddns-settings.php');
include('qddns-dashboard.php');
include('qddns-pluginlinks.php');
include('qddns-shortcode.php');
include('qddns-widget.php');
include('qddns-userprofile.php');
?>
