<?php
$string=$_GET['adsg_post_id']; 
$post_ids=explode("***", $string);
$wp_array=array();
foreach ($post_ids as $key => $value) {
	$post = get_post($value);
	if($post)
	$wp_array[$value]=$post->post_title;
}
$data_return=json_encode($wp_array);
echo $data_return;die;
die;

?>
