<?php
/*
 * Plugin name: Quick DynDNS
 * Plugin URI: http://jlong.co
 * Description: A simple plugin to show client IP address information anywhere on your site, and provide Dynamic DNS services.
 * Version: 1.0.18
 * Author: Jarren Long
 * Author URI: http://jlong.co
 * License: GPLv3
 */
/*
Copyleft 2016 Jarren Long (jarrenlong@gmail.com)

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
$qddns_db_version = '1.0.18';

/* Plugin CSS */
wp_enqueue_style('custom-style', plugins_url( 'css/style-qddns.css', __FILE__ ), array(), 'all');

include('qddns-core.php');
include('qddns-install.php');
include('qddns-settings.php');
include('qddns-dashboard.php');
include('qddns-contactlink.php');
include('qddns-shortcode.php');
include('qddns-widget.php');
include('qddns-userprofile.php');

?>
