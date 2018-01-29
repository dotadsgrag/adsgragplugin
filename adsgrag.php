<?php
/**
*Plugin Name: Adsgrag
*Description:
*Version: 1.0
*Author: adsgrag.com
*Author URI: http://adsgrag.com/
*
*Text Domain:adsgrag
*/


if ( ! defined( 'ABSPATH' ) )
	 exit;

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

//ob_start();

//error_reporting(0); 
define('XYZ_ADSGRAG',__FILE__);
define('XYZ_ADSGRAG_DIR',dirname( plugin_basename( __FILE__ ) ));
define('XYZ_BASE','http://adsgrag.com/');


define('XYZ_PLUGIN_NAME','ADSGRAG');

global $wpdb;
$wpdb->query('SET SQL_MODE=""');
require( dirname( __FILE__ ) . '/includes/install.php' );
require( dirname( __FILE__ ) . '/includes/menu.php' );
//require( dirname( __FILE__ ) . '/includes/widget.php' );
require( dirname( __FILE__ ) . '/includes/uninstall.php' );
require( dirname( __FILE__ ) . '/shortcode-handler.php' );
require( dirname( __FILE__ ) . '/posts.php' );

?>
