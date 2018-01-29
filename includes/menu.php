<?php

if ( ! defined( 'ABSPATH' ) )
	 exit;
	 
add_action("admin_menu","xyz_adsg_menu");
if ( !function_exists( 'xyz_adsg_menu' ) ) {
	function xyz_adsg_menu()
	{
		$imgpath1=plugins_url(XYZ_ADSGRAG_DIR.'/images/');
		add_menu_page( "Adsgrag", "Adsgrag", "manage_options", "adsg_settings", "xyz_adsg_settings" ,$imgpath1.'s.png',30);
		add_submenu_page( "xyz_adsg_settings", "Settings", "Settings", "manage_options", "xyz_adsg_settings", "xyz_adsg_settings" );
		add_menu_page( "null", "Adunit Display Settings", 'manage_options', "adsg_adu_display", "xyz_adsg_adu_display" );
		remove_menu_page('adsg_adu_display');
	}
}

if ( !function_exists( 'xyz_adsg_settings' ) ) {
	function xyz_adsg_settings()
	{
		
		require( dirname( __FILE__ ) . '/settings.php' );
		
	}
}

if ( !function_exists( 'xyz_adsg_adu_display' ) ) {
	function xyz_adsg_adu_display()
	{
		
		require( dirname( __FILE__ ) . '/adu_display_settings.php' );
		
	}
}
if ( !function_exists( 'xyz_adsg_scripts' ) ) {
	function xyz_adsg_scripts()
	{   
	 wp_enqueue_script('jquery');
	 wp_register_style('xyz_adsg_style-admin', plugins_url(XYZ_ADSGRAG_DIR.'/css/admin-style.css'));
	 wp_enqueue_style('xyz_adsg_style-admin');
	 wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
}
add_action("admin_enqueue_scripts","xyz_adsg_scripts");
if ( !function_exists( 'xyz_adsg_user_scripts' ) ) {
	function xyz_adsg_user_scripts()
	{
		wp_enqueue_script('jquery');
	}
}
if(!(is_admin()))
{
	add_action("init","xyz_adsg_user_scripts");
}


?>