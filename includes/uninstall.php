<?php

if ( ! defined( 'ABSPATH' ) )
	 exit;
	 
if ( !function_exists( 'xyz_adsg_network_destroy' ) ) {
	function xyz_adsg_network_destroy($networkwide) {
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ($networkwide) {
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					xyz_adsg_uninstall();
				}
				switch_to_blog($old_blog);
				return;
			}
		}
		xyz_adsg_uninstall();
	}
}

if ( !function_exists( 'xyz_adsg_uninstall' ) ) {
	function xyz_adsg_uninstall(){
		global $wpdb;
	}
}
register_uninstall_hook( XYZ_ADSGRAG, 'xyz_adsg_network_destroy' );
?>