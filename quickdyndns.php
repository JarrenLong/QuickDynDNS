<?php
/*
Plugin Name: Quick DynDNS
Plugin URI: https://www.booksnbytes.net/quickdyndns
Description: Quick DynDNS provides free and premium Dynamic DNS services for your members, allowing them to know their current IP address wherever they are.
Version: 1.0.65
Author: Jarren Long
Author URI: https://www.booksnbytes.net
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $qddns_db_version;
$qddns_db_version = '1.0.65';

/* Plugin JS and CSS */
wp_enqueue_script( 'scripts-qddns-jquery', plugins_url( 'js/jquery-3.2.1.min.js', __FILE__ ), array(), true);
wp_enqueue_style('styles-qddns', plugins_url( 'css/style-qddns.css', __FILE__ ), array(), 'all');

function plugin_name() {
	return plugin_basename( __FILE__ ); 
}

$enabled = get_option('qddns_enabled');

if ($enabled) {
  include('qddns-rewrite.php');
}
include('qddns-core.php');
include('qddns-install.php');
include('qddns-settings.php');
if ($enabled) {
  include('qddns-dashboard.php');
  include('qddns-pluginlinks.php');
  include('qddns-shortcode.php');
  include('qddns-widget.php');
  include('qddns-userprofile.php');
}
?>
