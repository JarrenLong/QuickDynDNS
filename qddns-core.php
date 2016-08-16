<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function qddns_insert_data($uid, $ip, $src = '') {
	global $wpdb;
	
	$wpdb->insert( 
		$wpdb->prefix . 'qddns_iplog', 
		array( 
			'user_id' => $uid,
			'time' => current_time( 'mysql' ), 
			'ip_address' => $ip,
			'source' => $src
		) 
	);
}

function get_client_ip($src, $log = true, $auth = '') {
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	$ip = apply_filters( 'wpb_get_ip', $ip );
	
	// Save IP to database table
	if( $log ) {
		$uid = get_current_user_id();
		if( $uid > 0 )
			qddns_insert_data( $uid, $ip, $src );
		else
			qddns_insert_data( auth_to_uid( $auth ), $ip, $src );
	}
	
	return $ip;
}

function ip_address_to_hostname($ip) {
	$host = @gethostbyaddr( $ip );
	if( !$host || $host == $ip )
		$host = 'Unknown';
	
	return host;
}

function get_num_valid_users() {
	global $wpdb;
	
	return count( $wpdb->get_results( "SELECT DISTINCT user_id FROM " . $wpdb->prefix . 'qddns' ) );
}

function get_request_stats_table_by_day($src = '') {
	global $wpdb;
	
	$sql = "SELECT DATE(time) day, source, COUNT(source) requests FROM " . $wpdb->prefix . 'qddns_iplog WHERE MONTH(time) = MONTH(NOW()) AND YEAR(time) = YEAR(NOW())';
	
	if( !empty($src))
		$sql .= " AND source = '" . $src . "'";
	
	$sql .= " GROUP BY day, source ORDER BY day";
	
	return $wpdb->get_results( $sql );
}
function get_request_stats_table($src = '') {
	global $wpdb;
	
	$sql = "SELECT time, source FROM " . $wpdb->prefix . 'qddns_iplog WHERE MONTH(time) = MONTH(NOW()) AND YEAR(time) = YEAR(NOW())';
	
	if( !empty($src))
		$sql .= " AND source = '" . $src . "'";
	
	return $wpdb->get_results( $sql );
}
function get_request_stats_table_count($src = '') {
	return count( get_request_stats_table( $src ) );
}

function auth_to_uid($auth) {
	global $wpdb;
	
	$sql = "select user_id from " . $wpdb->prefix . "usermeta WHERE meta_key = 'qddns_client_auth_token' AND meta_value = '" . $auth . "'";
	$rec = $wpdb->get_results( $sql );
	if( count( $rec ) > 0)
		return $rec[0]->user_id;
	
	return 0;
}

function current_user_has_auth($auth = '') {
	global $wpdb;
	
	if( !get_option( 'qddns_enable_user_auth' ) )
		return false;
	
	if($auth == '') {
		$uid = get_current_user_id();
		if( $uid > 0 ) {
			$sql = "SELECT id FROM " . $wpdb->prefix . 'users WHERE id = ' . $uid;
			
			return count( $wpdb->get_results( $sql ) ) > 0;
		}
	} else {
		$sql = "select user_id from " . $wpdb->prefix . "usermeta WHERE meta_key = 'qddns_client_auth_token' AND meta_value = '" . $auth . "'";
		
		return count( $wpdb->get_results( $sql ) ) > 0;
	}
	
	return false;
}

function create_user_auth_token() {
	return substr( bin2hex( wp_hash( get_current_user()->user_login . current_time( 'mysql' ) . $wpdb->prefix ) ), 0, 32 );
}

// TODO: These are borrowed from a few other plugins, need to be modified
function qddns_decide_subdomain() {
	$url = getenv( 'HTTP_HOST' ) . getenv( 'REQUEST_URI' );
	$subdomains = explode( ".", $url );
	$subdomain	= $subdomains[0];

	// return false if not a category https://codex.wordpress.org/Function_Reference/get_category_by_slug
	$category =  get_category_by_slug( $subdomain );

	if ( $category && is_array( $this->selected_categories ) ) {
		$category_term_id = $category->term_id;

		//if child category is supposed to be subdomain, change to main category id
		if(	$this->settings[ 'child_categories' ] == 'all_subdomain' ) {
			if( $parent_cat = $this->get_the_ancestor( $category ) ) {
				$category_term_id  = $parent_cat->term_id;
			}
		}

		foreach ( $this->selected_categories as $id => $special_setting) {
			if ( $id == $category_term_id ) {
				$this->subdomain = $category;

				if( is_array($special_setting) )
					$this->settings = array_merge($this->settings,$special_setting);
				$this->subdomain_home = $this->replace_to_subdomain_link( $category->slug , $this->home_url );

				break;
			}
		}
	}
}

function qddns_redirect() {
	if ( 0 == $this->settings[ 'redirect' ] || ! $this->run  ) {
		return;
	}

	$requested_url = 'http' . (empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "") . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;
	$requested_url = strtolower($requested_url);
	$requested_url = rtrim($requested_url,'/');
	/*
	$_SERVER['REQUEST_URI'] can contain "?dfd=dfd"
	so better to parse it to get real path
	*/

	$parse_requested_url = parse_url( $requested_url );

	/*
	* check main domain
	*/
	$is_main_domain = false;
	$parse_home_url = parse_url( $this->home_url );
	if( $parse_home_url['host'] == $parse_requested_url['host'] )
		$is_main_domain = true;

	/*
	* redirect only work for main domain or subdomain created by this plugin
	* so if user has subdomain outside this plugin will not be affected
	*/
	if ( $this->subdomain ||   $is_main_domain ) {
		/*
		* Begin to verify redirection
		*/
		$redirect = false;
		$real_url = null;
		$status = 302; //testing plugin
		if( $this->run == 2 )
			$status = 301;

		if( is_single() ) {
			if( is_feed() ||  is_trackback() || is_attachment() ) {
				return;
			}

			global $post;

			$result = $this->decide_subdomain_post($post->ID);

			if( $result->subdomain) {
				$real_url = get_permalink($post->ID);
				$real_url = rtrim($real_url,'/');

				/*
				* wordpress didn't give comment page detection
				* but we can use something like this
				*/
				$cpage = get_query_var( 'cpage' );
				if ( $cpage > 0 ){
					$real_url = $real_url . '/comment-page-' . $cpage;
				}

				/*
				* it's for splited post using <!--nextpage-->
				* is_paged() not working here
				* cause default wp theme using something like this /post/4
				* rather than /post/page/4
				* we go to default
				*/
				$page_post = get_query_var( 'page', 1 );
				if ( $page_post > 1 ) {
					$real_url = $real_url . '/' . $page_post;
				}
			}
		} elseif ( is_category() ) {
			if ( is_feed() )
				return;

			global $cat;
			$current_cat = get_category( $cat );
			$parent_cat = $this->get_the_ancestor( $current_cat );
			$scc = $this->settings[ 'child_categories' ];

			if( $scc == 'main_categories_subdomains' || $scc == 'all_subdomain') {
				if ( $parent_cat && @array_key_exists( $parent_cat->term_id , $this->selected_categories ) ) {
					//yeah this category should be on subdomain
					$real_url = get_category_link($current_cat->term_id);
					$real_url = rtrim($real_url,'/');

					if ( is_paged() ) {
						$paged = get_query_var( 'paged', 1 );
						$real_url = $real_url . '/page/' .  $paged ;
					}
				}
			}
		}

		if( $real_url ) {
			$parse_real_url = parse_url( $real_url );
			if( $parse_real_url['host'] != $parse_requested_url['host'] || $parse_real_url['path'] != $parse_requested_url['path'] ) {
				$redirect = $real_url;
			}
		}

		if ( $redirect ) {
			//echo $redirect . ' ' . $status;
			wp_redirect( $redirect, $status );
			exit();
		}
	}
}
?>
