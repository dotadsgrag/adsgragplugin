<?php
if ( ! defined( 'ABSPATH' ) )
	 exit;
	 
if ( !function_exists( 'xyz_adsg_network_install' ) ) {
	function xyz_adsg_network_install($networkwide) {
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ($networkwide) {
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id)
				{
					switch_to_blog($blog_id);
					xyz_adsg_install();
				}
				switch_to_blog($old_blog);
				return;
			}
		}
		xyz_adsg_install();
	}
}
if ( !function_exists( 'xyz_adsg_install' ) ) {
	function xyz_adsg_install() {
		global $wpdb;
		$wpdb->show_errors(true);
		$queryForm = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."xyz_adsg_adunit_settings` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `aduid` int(11) NOT NULL,
		  `siteid` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `uid` int(11) NOT NULL,
		  `shortcode_handling` int(11) NOT NULL,
		  `adunit_position` varchar(255) NOT NULL,
		  `native` int(11) NOT NULL,
		  `displaytype` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1" ;
		$wpdb->query($queryForm);

		if(get_option('xyz_credit_link')=="")
		{
			add_option("xyz_credit_link", '0');
		}
		add_option("xyz_adsg_credit_dismiss", '0');
	}
}
register_activation_hook(XYZ_ADSGRAG, 'xyz_adsg_network_install' );
?>
