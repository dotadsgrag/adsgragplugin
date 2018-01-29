<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

global $wpdb;

$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$id=$_GET['id'];
if(!$id)
	return;

$name=$_GET['name'];
$data = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."xyz_adsg_adunit_settings WHERE aduid =".$id, ARRAY_A );

$shortcode=$data['shortcode_handling'];//die;
$position=$data['adunit_position'];
$position_arr=explode(',', $position);

$f=1;$msg1="";
$cl='';$adunits='';

$xyz_username=get_option('xyz_adsg_username');
$xyz_key=get_option('xyz_adsg_key');
$args=array(
	'public'   => true,
	'_builtin' => false
);

$output = 'objects'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types=get_post_types($args,$output,$operator);

if(isset($_POST) && isset($_POST['submit'])){
	$shortcode=1;
	$post=0;
	$page=0;
	$post_enable=0;
	$page_enable=0;
	$position_str='';
	
    if(isset($_POST['shortcode']))
		$shortcode=$_POST['shortcode'];
    if($shortcode==2)
    {
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
	    if(isset($_POST['post_display']))
	 		$post = $_POST['post_display'];
	    if(isset($_POST['page_display']))
			$page = $_POST['page_display'];
	    if(isset($_POST['post_ad_enable']))
			$post_enable=$_POST['post_ad_enable'];
	    if(isset($_POST['page_ad_enable']))
			$page_enable=$_POST['page_ad_enable'];

		$position_arr=array();
		$position_str='';
		if($post_enable)
			$position_arr[]='post_'.$post;
		if($page_enable)
			$position_arr[]='page_'.$page;

		foreach ( $post_types  as $post_type ) {
			if(isset($_POST[$post_type->name.'_display']))
			{
				$value=$_POST[$post_type->name.'_display'];
				if(isset($_POST[$post_type->name.'_ad_enable']))
					$position_arr[]=$post_type->name.'_'.$value;
			}
		}
		$position_str=implode(',', $position_arr);
    }
	if($shortcode=='' && ($post=="" || $page==""))           
	{ 
		$msg1="Please fill up the mandatory fields..!";
		$f=0;
	}

	if($f==1){
		$wpdb->update(
			$wpdb->prefix.'xyz_adsg_adunit_settings',
			array(
				'shortcode_handling' => $shortcode,	// string
				'adunit_position' => $position_str	// integer (number)
			),
			array( 'aduid' => $id ),
			array('%d','%s'),
			array( '%d' )
		);
		$msg1="Settings updated successfully";
    }

    $display='display:none';
	if($msg1!="")
	{
		$display='display:block';
		if($f==0)
			$cl="system_notice_area_style0";
		else if($f==1)
			$cl="system_notice_area_style1";
	}
}
?>

<div class="<?php  echo $cl; ?>" id="system_notice_area" style="<?php echo $display;?>">
<?php
echo $msg1;
?> &nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss" onclick="jQuery('#system_notice_area').fadeOut(1000)">Dismiss</span>
</div>
	<div class='wrap'>
		<h3>Adunit Display Settings <?php if($name) echo '- '.$name;?> </h3>
		<h4>All fields marked <span style=" color:red">*</span> are mandatory</h4>

		<form name="item_settings" method="post" id="item_settings" enctype="multipart/form-data" >
			<table  id="settings_table"  class="widefat"  style="width:99%;height:200px;">
				<thead>
					<tr><th><b>Settings</b></th>
						<th></th>
					</tr>
				</thead>
				<tr valign="top">
					<td scope="row" style="width:550px;">Ad Display</td>
					<td>
						<select name="shortcode" onchange="jQuery('#shortcode').toggle();jQuery('#autosetting').toggle();">
							<option <?php if($shortcode==1){echo "selected";}?> value="1">Manual</option>
							<option  <?php if($shortcode==2){echo "selected";}?> value="2">Automatic</option>
						</select>
					</td>
				</tr>
				<tr id="shortcode" <?php if($shortcode==2){?> style="display: none;" <?php }?>>
					<td>Shortcode</td>
					<td>[adsgrag id=<?php echo $id;?> status="Manual"]</td>
				</tr>
				<tr id="autosetting" <?php if($shortcode==1){?>style="display: none" <?php }?>>
					<td>
						<table>
							<tr>
								<td>Post Type</td>
								<td>Enable Ad Display</td>
								<td>Top</td>
								<td>Bottom</td>
							</tr>
							<tr>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td>Post</td>
								<td>
									<input type="checkbox" name="post_ad_enable"  id="post_ad_enable" value="1" onclick="enable_ad_disaply('post');" <?php if(in_array('post_1', $position_arr)|| in_array('post_2', $position_arr)){echo 'checked';}?>>
								</td>
								<td>
									<input type="radio" name="post_display" class="post_display"  value="1" <?php if(in_array('post_1', $position_arr) ){echo 'checked';}?>>
								</td>
								<td>
									<input type="radio" name="post_display" class="post_display"  value="2" <?php if(in_array('post_2', $position_arr) ){echo 'checked';}?>>
								</td>
							</tr>
							<tr>
								<td>Page</td>
								<td>
									<input type="checkbox" name="page_ad_enable"  id="page_ad_enable" value="1" onclick="enable_ad_disaply('page');" <?php if(in_array('page_1', $position_arr)|| in_array('page_2', $position_arr)){echo 'checked';}?>>
								</td>
								<td>
									<input type="radio" name="page_display"  class="page_display" value="1" <?php if(in_array('page_1', $position_arr) ){echo 'checked';}?> >
								</td>
								<td>
									<input type="radio" name="page_display" class="page_display" value="2" <?php if(in_array('page_2', $position_arr) ){echo 'checked';}?>>
								</td>
							</tr>
							<?php foreach ( $post_types  as $post_type ) {
							?>
							<tr>
								<td><?php echo $post_type->labels->singular_name;?></td>
								<td>
									<input type="checkbox" name="<?php echo $post_type->name;?>_ad_enable" id="<?php echo $post_type->name;?>_ad_enable" value="1" onclick="enable_ad_disaply('<?php echo $post_type->name;?>');" <?php if(in_array($post_type->name.'_1', $position_arr)|| in_array($post_type->name.'_2', $position_arr)){echo 'checked';}?>>
								</td>
								<td>
									<input type="radio" name="<?php echo $post_type->name;?>_display" class="<?php echo $post_type->name;?>_display" value="1" <?php if(in_array($post_type->name.'_1', $position_arr) ){echo 'checked';}?>>
								</td>
								<td>
									<input type="radio" name="<?php echo $post_type->name;?>_display" class="<?php echo $post_type->name;?>_display" value="2" <?php if(in_array($post_type->name.'_2', $position_arr) ){echo 'checked';}?>>
								</td>
							</tr>
							<?php
							}
							?>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row" colspan="3" style="width:550px;text-align: center;">
						<input type="submit" value="Update Settings"  class='submit_button'  name="submit" >
					</td>
				</tr>
			</table>
		</form>
	</div>
<script>
jQuery(document).ready(function (){
	enable_ad_disaply('post');
	enable_ad_disaply('page');
	<?php foreach ( $post_types  as $post_type ) {
	?>
	enable_ad_disaply('<?php echo $post_type->name ;?>');
	<?php
	}
	?>
});

function enable_ad_disaply(type)
{
	
	if(jQuery('#'+type+'_ad_enable:checked').length == 1){
		jQuery('.'+type+'_display').prop("disabled",false);
	}
	else jQuery('.'+type+'_display').prop("disabled",true);
}
</script>
