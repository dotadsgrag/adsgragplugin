<?php

if ( ! defined( 'ABSPATH' ) )
	 exit;

add_shortcode("xyz_wp_cls_home","xyz_cls_home_shortcode");

if(!function_exists('xyz_adsg_display'))
{
	function xyz_adsg_display($atts)
	{
		global $wpdb;
		
	 	$id = $atts['id'];
	 	$uname_ads = get_option( 'xyz_adsg_username' );
	 	$ads_activation_key = get_option( 'xyz_adsg_key' );
	 	if($ads_activation_key && $uname_ads)
		{ 
		$url='https://adsgrag.com/Beta1.0/adsgrag1.0/forms/user/getadunits.php?username='.$uname_ads.'&key='.$ads_activation_key;
		$adunits = file_get_contents($url);
		$adu_arr=json_decode($adunits);
		$ads_arr = $adu_arr[1];
		
		if ($ads_arr->{$id})
		{
		$a = "";
	 	$status = $atts['status'];
	 	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."xyz_adsg_adunit_settings WHERE aduid=%d",$id));
		$shortcode_status = $row->shortcode_handling;
		$pos_para = $row->adunit_position;
		if($status == "Manual")
		{
			$postion_data_para = 1;
		}
		else
		{
			$postion_data_para = 2;
		}
		
	 	
		$basepath=str_replace('https://','',XYZ_BASE.'Beta1.0/adsgrag1.0/js/ad-preview');
	 	$basepath=str_replace('http://','',$basepath);
	 	$url=base64_encode(site_url().'/');
	 	$path = plugins_url()."/adsgrag/";
	 	
	 	
	 	
	 	
	 	if($row->native == 1)
	 	{
	 		$native = "Native";
		}
	 	else
	 	{
	 		$native = "POP";
		}

		$ids = get_the_ID();
		$ptype= get_post_type( $ids );

	 	$position = $row->adunit_position;
	 	$position_parameter = explode("," ,$position);
	 	
	 	if (in_array($ptype."_1", $position_parameter))
	 	{
			$st = 1;
		}
		else if(in_array($ptype."_2", $position_parameter))
		{
			$st = 1;
		}
		else
		{
			$st = 0;
		}
	 	
	
	 	
	 	if($st == 1)
		{
			
		
	 	if($status == "Manual")
	 	{
			$adcode = "";
			
		}
		else if($status == "automatic")
	 	{
				
			if(is_category())
			{
				$a = "category";
			}
			else
			{
			if (in_array($ptype."_1", $position_parameter))
			{
				$a = "top";
			}
			else if(in_array($ptype."_2", $position_parameter))
			{
			    $a = "bottom";
			}
			else
			{
				$a = "bottom";
			}
			}
		$adcode ='<script type="text/javascript">var divcontiner = "display_ads_data_'.$id.'";var ids_val = "automatic_'.$id.'";var site_ids_data = "site_id_'.$id.$postion_data_para.'";</script>';
	 	$adcode .='<script src="https://adsgrag.com/Beta1.0/adsgrag1.0/common/js/lib/jquery/2.1.4/jquery.min.js"></script>';
	 	$adcode.='<script type="text/javascript" src="//'.$basepath.'/getbrowser.js"></script>';
	 	$adcode.='<script type="text/javascript" src="'.$path.'js/ads.js?siteId='.$row->siteid.'&position='.$a.'&adtype='.$native.'&wp=1" id="site_id_'.$id.$postion_data_para.'"></script>';
			
		}
		}
		else
		{
		$adcode ='<script type="text/javascript">var divcontiner = "display_ads_data_'.$ids.$id.'";var ids_val = "automatic_'.$id.'";var site_ids_data = "site_id_'.$id.$postion_data_para.'";</script>';
	 	$adcode .='<script src="https://adsgrag.com/Beta1.0/adsgrag1.0/common/js/lib/jquery/2.1.4/jquery.min.js"></script>';
	 	$adcode.='<script type="text/javascript" src="//'.$basepath.'/getbrowser.js"></script>';	
	 	if($status == "Manual")
	 	{
			$a = "auto";
			$adcode .= '<div class="section"><div id="display_ads_data_'.$ids.$id.'" class="show_ads_data container"></div></div>';
		}
		else
		{
			
			if(is_category())
			{
				$a = "category";
			}
			else
			{
			if (in_array($ptype."_1", $position_parameter))
			{
				$a = "top";
			}
			else if(in_array($ptype."_2", $position_parameter))
			{
			    $a = "bottom";
			}
			else
			{
				$a = "bottom";
			}
			}
			
		}
		$adcode.='<script type="text/javascript" src="'.$path.'js/ads.js?siteId='.$row->siteid.'&position='.$a.'&adtype='.$native.'&wp=1" id="site_id_'.$id.$postion_data_para.'"></script>';
		}
	 	
		}
		else
		{
			$adcode = "";
		}
		}
	 	
	 	
	 	
		return $adcode;
	}
}

add_shortcode("adsgrag","xyz_adsg_display");

if(!function_exists('xyz_adsg_display_automatic'))
{
	function xyz_adsg_display_automatic()
	{
		if(is_admin()) // Check if we are in admin
			return;
		global $wpdb;
		global $post;
		$data = $wpdb->get_results("SELECT adunit_position,aduid FROM ".$wpdb->prefix."xyz_adsg_adunit_settings  WHERE shortcode_handling=2");
		if(count($data))
		{
			foreach ($data as $key=>$value)
			{
				$position=$value->adunit_position;
				$position_arr=explode(',', $position);
				$aduid=$value->aduid;
				$id=get_the_ID();
				if(!is_category())
				{
				$ids = $post->ID;
				$ptype= get_post_type( $ids );
				if(in_array($ptype."_2", $position_arr))
				{
					add_action('get_footer'	 , 'add_div_after_fooetr');	
				}
				else
				{
				
				add_filter( 'the_content', function( $content) use ( $aduid) {
					return xyz_adsg_ads( $content, $aduid,'automatic');
				}, '');
				}
				}
				else
				{
				
					add_action ('get_the_archive_title' , 'add_div_after_header');
					add_action('get_footer'	 , 'add_div_after_fooetr');
				
					
				}
			}
		}
	} 
}
add_action("wp","xyz_adsg_display_automatic");



function add_div_after_header() {
	global $wpdb;
	global $post;
	$data = $wpdb->get_results("SELECT adunit_position,aduid FROM ".$wpdb->prefix."xyz_adsg_adunit_settings  WHERE shortcode_handling=2");

	$title2 = single_cat_title( '', true);
	if(count($data))
	{
		foreach ($data as $key=>$value)
		{
			$aduid = $value->aduid;
			$position = $value->adunit_position;
			$position_parameter1 = explode("," ,$position);
			$ids = $post->ID;
			$ptype= get_post_type( $ids );
			
			if(in_array($ptype."_1", $position_parameter1))
			{
				$title2 .= '<div class="section"><div id="automatic_'.$aduid.'" class="test_data_get container"></div></div>';
				$title2 .= do_shortcode("[adsgrag id='".$aduid."' status='automatic']");
				$title2 .=  "</div>";
				
			}
			
		}
		
	}
	
	return $title2;
}
function add_div_after_fooetr()
{
	
	global $wpdb;
	global $post;
	$data = $wpdb->get_results("SELECT adunit_position,aduid FROM ".$wpdb->prefix."xyz_adsg_adunit_settings  WHERE shortcode_handling=2");
	if(count($data))
	{
		foreach ($data as $key=>$value)
		{
			$aduid = $value->aduid;
			$position=$value->adunit_position;
			$position_parameter1 = explode("," ,$position);
			$ids = $post->ID;
			$ptype= get_post_type( $ids );
			
			if(in_array($ptype."_2", $position_parameter1))
			{
				
				echo '<div class="section"><div id="automatic_'.$aduid.'" class="test_data_get container"></div></div>';
				echo do_shortcode("[adsgrag id='".$aduid."' status='automatic']");
				echo "</div>";
				
			}
		}
	}
}
	



if(!function_exists('xyz_adsg_ads'))
{
	function xyz_adsg_ads($data,$aid,$status)
	{  
		$id=get_the_ID();
		if ($id) 
		{
			if(current_filter()=='the_title')
			{
				if ( in_the_loop() ){
					return do_shortcode("[adsgrag id='".$aid."' status='".$status."']").$data;
				} else { 
					return $data;
				}
			}
			else if(current_filter()=='the_content')
			{
				if ( in_the_loop() ){
					return $data.do_shortcode("[adsgrag id='".$aid."' status='".$status."']");
				} else { 
					return $data;
				}
			}
		} 
		return $data;
	}
}

function theme_slug_filter_the_title( $title ) {
	if(is_admin())
	{
	return $title;
	}
	else
	{
	if(!is_category())
	{	
	global $wpdb;
	$data = $wpdb->get_results("SELECT aduid,adunit_position FROM `wp_xyz_adsg_adunit_settings` WHERE adunit_position != ''");
	
	if(count($data))
		{
			foreach ($data as $key=>$value)
			{
				
				$aduid=$value->aduid;
				$position=$value->adunit_position;
				$position_parameter = explode("," ,$position);
				$ids = get_the_ID();
				$ptype= get_post_type( $ids );
				if (in_array($ptype."_1", $position_parameter))
				{
					$custom_title = '<div class="section"><div id="automatic_'.$aduid.'" class="container"></div></div>';
					$title .= $custom_title;
				}
				 
			}
		}	
   
    return $title;
	}
	else
	{
		return $title;
	}
}

}
add_filter( 'the_title', 'theme_slug_filter_the_title' );

function style_head() {
	?>
<style>
.title_div {
    float: left;
    width: 100%;
}

.title_div > a {
  border: medium none !important;
  box-shadow: none !important;
  color: rgb(255, 0, 0);
  float: left;
  font-size: 12px;
  font-weight: bold;
  text-align: center;
  width: 100%;
}
.widget .row .col-lg-4.col-md-4.col-sm-6.col-xs-12 {
  min-height: 110px;
}
.widget .row .col-lg-4.col-md-4.col-sm-6.col-xs-12 > img {
  height: 60px !important;
}
.widget .title_div > a {
overflow-wrap: break-word;
  line-height: 12px;
}
.content-area {
  margin-right: 0;
}

.adimg {
    height: 200px;
}
.adimg > img {
    height: 100% !important;
}
.sidebar {
  float: left;
  padding: 50px 0 0 15px;
  width: 25%;
  margin-left: 0px;
}
.test_data_get {
  display: inline-block;
  width:100%;
}
.titleTop h3 {
    color: rgb(0, 0, 0);
    font-size: 42px !important;
    font-weight: 500;
    margin-bottom: 40px;
    position: relative;
}
li {
  list-style: outside none none;
}
a {
	 box-shadow: none !important;
}
a:hover {
	 box-shadow: none !important;
}
.site-content {
  background: rgb(246, 246, 246) none repeat scroll 0 0;
}
.gridlist li h3 a {
  color: rgb(30, 144, 255);
  display: inherit;
  font-size: 14px !important;
  font-weight: bold;
  line-height: 19px;
  text-align: center;
}
.container {
    width: 100% !important;
}
</style>
	<?php
}
add_action( 'wp_head', 'style_head', 10, 2 ); 

/* add css in frontend */
 
 function add_scripts()
 {
	 wp_enqueue_style( 'main-css', plugins_url().'/adsgrag/css/main.css');
	 wp_enqueue_style( 'bootstrap-css', plugins_url().'/adsgrag/css/bootstrap.min.css');
 }
 add_action( 'wp_enqueue_scripts', 'add_scripts');

?>
