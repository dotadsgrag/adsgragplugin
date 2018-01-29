<?php 
$count=$_GET['adsg_count'];
$args = array(
		'numberposts' => $count,
		//'offset' => 0,
	    //'category' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		//'include' => 'ID,post_title',
		//'exclude' => 'post_author,post_date,post_date_gmt,post_excerpt,post_status,comment_status,ping_status,post_password,post_name,to_ping,pinged,post_modified,post_modified_gmt,post_content_filtered,post_parent,menu_order,post_type,post_mime_type,comment_count,filter',
		//'meta_key' => '',
		//'meta_value' =>'',
		'post_type' => 'post',
		'post_status' => ' publish',
		'suppress_filters' => true
);
$new_posts=array();
$recent_posts = wp_get_recent_posts( $args );
foreach ($recent_posts as $post)
{
	$img= wp_get_attachment_url( get_post_thumbnail_id( $post['ID'] ) );
	$new_posts[]=array('aid'=>$post['ID'],'title'=>$post['post_title'],'description'=>$post['post_content'],'img'=>$img,'url'=>$post['guid'],'author'=>$post['post_author']);
}
echo json_encode($new_posts);
//print_r($recent_posts);
die;

?>
