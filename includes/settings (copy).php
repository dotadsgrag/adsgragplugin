<?php
if ( ! defined( 'ABSPATH' ) )
	 exit;

global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);

$f=1;$msg1="";
$cl='';
$adunits='';
$xyz_username=get_option('xyz_adsg_username');
$xyz_key=get_option('xyz_adsg_key');

if(isset($_POST) && isset($_POST['submit'])){
 	$xyz_username=$_POST['xyz_adsg_username'];
	$xyz_key=$_POST['xyz_adsg_key'];
	
	if($xyz_username==""||$xyz_key=="")            
	{ 
		$msg1="Please fill up the mandatory fields..!";
		$f=0;
	}

	if($f==1){
		$adunits=get_adunits($xyz_username,$xyz_key);
       	if($adunits=='error1')
       		$msg1="Invalid Username or Key";
       	else if($adunits=='error2')
	       	$msg1="No Adunits Found";
    	else{
	       	$adu_arr=json_decode($adunits);
	       	if(count($adu_arr[1])){
    	   		$wpdb->delete( $wpdb->prefix.'xyz_adsg_adunit_settings', array('uid' => $adu_arr[0]), array('%d' ));
		       	foreach ($adu_arr[1] as $key=>$value)
	       		{
		       		$wpdb->insert(
	       				$wpdb->prefix.'xyz_adsg_adunit_settings',
	       				array(
       						'aduid' => $key,
       						'siteid' => $value[3],
       						'name'=>$value[0],
       						'uid' => $adu_arr[0],
       						'shortcode_handling'=>1,
       						'displaytype' => $value[2],
       						'native' => $value[1]
	       				),
	       				array('%d','%s','%s','%d','%d','%d','%d','%d','%d')
		       		);
		       	}
       		}
			update_option('xyz_adsg_username',$xyz_username);
			update_option('xyz_adsg_key',$xyz_key);
			$msg1="Settings updated successfully";
	    }
	}

	$display='display:none';
	if( $msg1!="")
	{
		$display='display:block';
		
		if($f==0)
			$cl="system_notice_area_style0";
		else if($f==1)
			$cl="system_notice_area_style1";
	}
}
else 
{
	$adunits=get_adunits($xyz_username,$xyz_key);
}
?>
<div class="<?php  echo $cl; ?>" id="system_notice_area" style="<?php echo $display;?>">
	<?php
	echo $msg1;
	?> &nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss" onclick="jQuery('#system_notice_area').fadeOut(1000)">Dismiss</span>
</div>
<div class='wrap'>
	<h3>Adsgrag Settings</h3>
	<h4> All fields marked <span style=" color:red">*</span> are mandatory</h4>
	<form name="item_settings" method="post" id="item_settings" enctype="multipart/form-data" >
		<table  id="settings_table"  class="widefat"  style="width:99%;height:200px;">
			<thead>
				<tr>
					<th>
						<b>Settings</b>
					</th>
					<th></th>
				</tr>
			</thead>
			<tr valign="top">
				<td scope="row" style="width:550px;">Adsgrag Username </td>
				<td>
					<input type="text" value="<?php echo $xyz_username;?>" name='xyz_adsg_username'>
					<span style=" color:red">*</span>
				</td>
			</tr>

			<tr valign="top">
				<td scope="row" style="width:550px;">Plugin Activation Key</td>
				<td>
					<input type="text" value="<?php echo $xyz_key;?>" name='xyz_adsg_key'>
					<span style=" color:red">*</span>
				</td>
			</tr>

			<tr valign="top">
				<td scope="row" style="width:550px;text-align: center;">
					<input type="submit" value="Update Settings"  class='submit_button'  name="submit" >
				</td>
			</tr>
		</table>
	</form>
<?php if($adunits){
	$adu_arr=json_decode($adunits);
	if(count($adu_arr[1])){
?>
	<h3>Adunits of <?php echo $xyz_username?></h3>
	<table    class="widefat"  style="width:99%;height:200px;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Operations</th>
			</tr>
		</thead>
	<?php
	foreach ($adu_arr[1] as $key => $value){?>
		<tr valign="top">
			<td scope="row" style="width:550px;"><?php echo $value[0]?></td>
			<td>
				<?php 
				if($value[2]==9){ echo 'POP';}else {echo 'Native';} ?>
			</td>
			<td>
				<a href="<?php echo admin_url('?page=adsg_adu_display&action=edit&id='.$key.'&name='.$value[0]);?>">Adunit Display Settings</a>
			</td>
		</tr>
	<?php }?>
	</table>
<?php }}?>
</div>
<?php 
function get_adunits($xyz_username,$xyz_key)
{
	$adunits='';
	if($xyz_key && $xyz_username)
	{ 
		$url='https://adsgrag.com/Beta1.0/adsgrag1.0/forms/user/getadunits.php?username='.$xyz_username.'&key='.$xyz_key;
		/*if (function_exists('curl_init'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			$adunits=$content;
		}
		else if(ini_get('allow_url_fopen')==1)*/
			$adunits=file_get_contents($url);
			echo "<pre>";
			print_r($adunits);
			echo "</pre>";
		return $adunits;
	}
}
?>
