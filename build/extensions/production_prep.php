<?php
// Right now these functions assume we are on either the current season's
// productions page, or any single page for a production (from any year)


// Must be inside loop
function get_cast_bio_url($season_year){
	$args=array(
		'post_type' => 'bio',
		'bio_year' => $season_year,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$my_bios_query = new WP_Query($args);

    if( $my_bios_query->have_posts() ) {
	    $permalink_to_cast_bios = get_bloginfo('url') . '/?bio_year=' . $season_year;
	}else{
		$permalink_to_cast_bios = FALSE;
	}
    wp_reset_query();

    return $permalink_to_cast_bios;
}
// Must be inside loop
function get_season_year(){
	global $post;
	$season_year = get_query_var('season_year');

	//If get_query_var returns null/False, then this is a page with a single show... so:
	if($season_year==FALSE){
		$terms = get_the_terms( $post->ID, 'season_year');
		foreach($terms as $term){
			$season_year = $term->name;
			if($season_year < $term->name) $season_year = $term->name;
		}
	}
	return $season_year;
}
// Must be inside loop
function get_production_photo_galleries_info($season_year){
	global $post;
	$args=array(
		'post_type' => 'production_photo',
		'photo_year' => $season_year,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$my_photo_query = new WP_Query($args);
	
    if( $my_photo_query->have_posts() ) {
		while($my_photo_query->have_posts()) { $my_photo_query->the_post();
			//create array with title of production as key and permalink as value
			$production_photo_galleries[the_title('','',false)] = get_permalink($post->ID);
		}
	}else{
		//create empty array to avoid errors below
		$production_photo_galleries = array();
	}
    wp_reset_query();
    return $production_photo_galleries;
}


add_filter('the_content','production_prep');
function production_prep($content){
	global $post;
	$on_production_page_or_current_season = ( get_query_var('post_type') == 'production' || get_query_var('season_year') != false);
	if (! $on_production_page_or_current_season) return $content;
	
	static $run_once = true;
	static $season_year, $permalink_to_cast_bios, $production_photo_galleries;

	if ($run_once) {
		// Get all the necessaries for injecting content into production posts.
		$season_year = get_season_year();
		$permalink_to_cast_bios = get_cast_bio_url($season_year);
		//Get all the season year production photo titles to later compare with each show title.
		$production_photo_galleries = get_production_photo_galleries_info($season_year);
	}
	$run_once = false;

	$production = ProductionInfo::get();
	$sponsor_thumbnail = '';
	if ( class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail('production', 'sponsor-image') ) {
		$post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('production','sponsor-image', $post->ID);
		$url = wp_get_attachment_image_src( $post_thumbnail_id, 'sponsor-sized' );
		$sponsor_thumbnail = '<img class="sponsor-image" src="' . $url[0] . '" />';
	}
    $date_range = '<div class="running-dates">'.$production->date_list.'</div>';
    $date_message = '<div class="dates-message">'.$production->message.'</div>';
	if($permalink_to_cast_bios){ 
		$cast_link = '<a href="'.$permalink_to_cast_bios.'">Meet the '.$season_year.' Cast</a>';
	}else{ $cast_link = ''; }
	if(array_key_exists(the_title('','',false), $production_photo_galleries)){
		$gallery_link = '<a href="'.$production_photo_galleries[the_title('','',false)].'" class="photo-gallery">View Photo Gallery</a>';
	}else{ $gallery_link = ''; }

	// build html
	$before_html = '';
	$before_html .= '<div class="writers">'.$production->writers.'</div>';
	//$before_html .= $date_range; //Broken. Check Class file for fix...
	$before_html .= $date_message;
	$before_html .= $cast_link;
	$before_html .= $gallery_link;

	//build html
	$after_html = '';
	//$after_html .= $sponsor_thumbnail;

	//pop sponsor into $content
	$content = '<div>'.$content.$sponsor_thumbnail.'</div>';

	return $before_html.$content.$after_html;
}

//add_filter('the_content','test_the_content',99);
function test_the_content($content){
	static $runs_left = 1;
	if ($runs_left == 0) return $content;
	$runs_left--;

	ddprint($content,false,true);
	return $content;
}

