<?php
get_header();

if(is_home()){
	$not_blog = get_cat_id('Not in Blog');
	global $query_string;
	query_posts($query_string . "&caller_get_posts=1&cat=-{$not_blog}");
}

if(is_front_page()){
	include 'daily_glance.php';
}

if(is_front_page()){
	get_template_part( 'loop', 'front' ); 
}elseif($wp_query->query_vars['post_type']=='production'){
	//This should only go through on single pages:
	get_template_part('loop','productions');
}else{
	get_template_part( 'loop', 'index' );
}
			
get_footer();?>