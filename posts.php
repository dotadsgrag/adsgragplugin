<?php
if ( ! defined( 'ABSPATH' ) )
	 exit;

function xyz_adsg_query_vars($vars) {
	$vars[] = 'adsg_post';
	$vars[] = 'adsg_count';
	$vars[] = 'adsg_post_id';
	return $vars;
}

add_filter('query_vars', 'xyz_adsg_query_vars');

if(!function_exists('xyz_adsg_parse_request'))
{
	function xyz_adsg_parse_request($wp) {
		if (array_key_exists('adsg_post', $wp->query_vars) && $wp->query_vars['adsg_post'] == 'adsg_recent'&& array_key_exists('adsg_count', $wp->query_vars)) {
			require( dirname( __FILE__ ) . '/posts/recent-posts.php' );
			die;
		}
		
		if (array_key_exists('adsg_post_id', $wp->query_vars)&& $wp->query_vars['adsg_post_id']>0) {
			require( dirname( __FILE__ ) . '/posts/get_post_title.php' );
			die;
		}
	}
}
add_action('parse_request', 'xyz_adsg_parse_request');
?>
