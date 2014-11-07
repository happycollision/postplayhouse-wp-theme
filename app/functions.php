<?php
require_once('extensions/bio_post-type.php');
require_once('extensions/production_photos_post-type.php');
require_once('extensions/slideshow_post-type.php');
require_once('extensions/Class_production_info_view.php');

require_once('extensions/kill_comments.php');
require_once('extensions/tiny_mce_changes.php');

require_once('extensions/calendar_creator.php');
require_once('extensions/production_prep.php');

remove_theme_support('post-formats');

add_theme_support( 'post-thumbnails' );
remove_post_type_support('page','thumbnail');
add_image_size('Today Box',200,200,true);
add_image_size('sponsor-sized',200,150,false);
add_image_size('Slideshow',900,506,true);

//Sponsor image
if(class_exists('MultiPostThumbnails')){
	new MultiPostThumbnails(array(
		'label' => 'Sponsor Image',
		'id' => 'sponsor-image',
		'post_type' => 'production'
		)
	);
}

add_filter('wp_nav_menu','post_playhouse_nav_menu',10,2);
add_filter('wp_page_menu','post_playhouse_nav_menu',10,2);
function post_playhouse_nav_menu($menu, $args){
	if ( is_object($args) ){
		$location = $args->theme_location;
	}else{
		$location = $args['theme_location'];
	}
	if ( $location != 'primary') return $menu;
	ob_start();
		include('extensions/nav.php');
	return ob_get_clean();
}

add_filter('post_thumbnail_size', 'resize_bio_thumbnails');
function resize_bio_thumbnails($size){
	if (is_tax('position')) {
		return 'medium';
	}
	return $size;
}

add_filter('the_author', 'remove_data');
add_filter('author_link', 'remove_data');
function remove_data($content) { return ""; }

add_filter('the_content','calendar_hc_creator',99);
function calendar_hc_creator($content){
	if ( is_page('calendar') && is_main_query() && in_the_loop() ){
		return make_hc_calendar();
	}
	return $content;
}

function get_slideshow_slides($return_type = 'array'){
	global $hc_slideshow_slides;

	if (gettype($hc_slideshow_slides) != 'array'){
		$slideshow = new WP_Query('post_type=slide&posts_per_page=-1');
		$slides = array();
		while ($slideshow->have_posts()) : $slideshow->the_post();
			if(has_post_thumbnail()){
				//based on size created by additional image sizes plugin
				$image = wp_get_attachment_image_src(get_post_thumbnail_id(),'large')[0];
				$title = get_the_title();
				$caption = get_the_excerpt();
				$link = get_post_meta(get_the_ID(),'hc_slide_link', true);
				$link = $link ? 'http://'.$link : '';


				$slides[] = array('image' => $image, 'title' => $title, 'caption' => $caption, 'link' => $link);
			} 
		endwhile;
		$hc_slideshow_slides = $slides;
	}
	return ($return_type=='json') ? json_encode($hc_slideshow_slides) : $hc_slideshow_slides;
}
function get_slideshow_slide_image($index=0){
	return get_slideshow_slides('array')[$index]['image'];
}

add_action('wp_head','add_favicons');
function add_favicons(){
	$stylesheet_directory = get_stylesheet_directory_uri();
	
	echo "<link rel='shortcut icon' href='{$stylesheet_directory}/favicon.ico'> \n";
	
	//echo "<link rel='apple-touch-icon' href='{$stylesheet_directory}/apple-touch-icon-precomposed.png' />\n";
	//echo "<link rel='apple-touch-icon' sizes='72x72' href='{$stylesheet_directory}/apple-touch-icon-72x72-precomposed.png' />\n";
	//echo "<link rel='apple-touch-icon' sizes='114x114' href='{$stylesheet_directory}/apple-touch-icon-114x114-precomposed.png' />\n";
	//echo "<link rel='apple-touch-icon' sizes='144x144' href='{$stylesheet_directory}/apple-touch-icon-precomposed.png' />\n";
}


/**
 * Here are some customizations that change text output via the gettext filter.
 * This was intended for translating themes to other languages, but why not
 * use it for more customization?
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 *
 */
add_filter( 'gettext', 'happycol_change_excerpt_name', 20, 3 );
function happycol_change_excerpt_name( $translated_text, $text, $domain ) {
    global $wp_query;
    if( is_post_type_archive('production') ) {
       $translated_text = 'Past Productions';
    }
    if ( is_tax('season_year') ){
    	$translated_text = 'Summer '.highest_season_year().' Productions';
    }
    if ( is_tax('position') && $translated_text == 'Archives' ){
    	//ddprint($wp_query);
    	$year = ' for Summer ' . $wp_query->query_vars['bio_year'];
    	switch($wp_query->query_vars['position']){
    		case 'castcrew':
    			$position = 'Cast and Crew' . $year;
    			break;
    		case 'musicians':
    			$position = 'Musicians' . $year;
    			break;
    		case 'staff':
    			$position = 'Staff';
    			break;
    		case 'board':
    			$position = "Board Members";
    			break;
    		default:
    			$position = 'Bios';
    			break;
    	}
    	$translated_text = $position;
    }
    return $translated_text;
}


add_action('wp_enqueue_scripts','enqueue_extra_styles',99);
function enqueue_extra_styles(){
	if (is_page('calendar')){
		wp_enqueue_style( 'calendar-styles', get_stylesheet_directory_uri().'/css/calendar.css');
	}
}

// Boolean. Are tickets currently available?
function tickets_available(){
	return true;
}
// Echos the class corresponding to the current availability of tickets
function tickets_class(){
	$class_name = (tickets_available()) ? 'tickets-available' : 'tickets-unavailable';
	echo $class_name;
}


add_action('wp_enqueue_scripts','enqueue_post_playhouse_scripts');

function enqueue_post_playhouse_scripts(){
	wp_register_script(
		'jquery-ui'
		,'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js'
		,array('jquery')
	);
	wp_enqueue_script(
		'head-scripts'
		,get_stylesheet_directory_uri().'/lib/head.js'
	);
	wp_enqueue_script(
		'foot-scripts'
		,get_stylesheet_directory_uri().'/lib/foot.js'
		,array('jquery')
		,false
		,true
	);
	if(is_front_page() || is_category(3) ){
		wp_enqueue_style(
			'jquery_ui_css'
			,'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css'
			,array()
			,'1'
			,'all'
		);
		wp_enqueue_style(
			'nivo_css'
			,get_stylesheet_directory_uri().'/lib/nivo_slider/nivo-slider.css'
			,array('jquery_ui_css')
			,'1'
			,'all'
		);
		wp_enqueue_script(
			'nivo_slider'
			,get_stylesheet_directory_uri().'/lib/nivo_slider/jquery.nivo.slider.js'
			,array('jquery')
			,'1'
			,false 
		);
		wp_enqueue_script(
			'front_page_scripts'
			,get_stylesheet_directory_uri().'/lib/front_page_scripts.js'
			,array('jquery-ui','nivo_slider')
			,'1'
			,true 
		);
	}
	if(is_page('calendar')){
		wp_enqueue_script(
			'calendar_scripts'
			,get_stylesheet_directory_uri().'/lib/calendar_scripts.js'
			,array('jquery')
		);
	}
}

add_action( 'parse_request', 'front_page_announcements' );
function front_page_announcements( $wp ) {
  //ddprint($query);
  // triggered also in admin pages
  if ( is_admin() ) return;

  // you should check also for page slug, because when pretty permalink are active
  // WordPress use 'pagename' query vars, not 'page_id'
  $id = isset($wp->query_vars['page_id']) && (int) $wp->query_vars['page_id'] === 84;
  $name = isset($wp->query_vars['pagename']) && $wp->query_vars['pagename'] === 'front-page';

  if ( $id || $name || empty($wp->query_vars) ) {
    if ( $id ) unset( $wp->query_vars['page_id'] );
    if ( $name ) unset( $wp->query_vars['pagename'] );
    $wp->query_vars['category_name'] = 'special-announcement';
  }
}

add_filter( 'template_include', 'front_page_template', 99 );
function front_page_template( $template ) {
	if ( is_category( 'special-announcement' )  ) {
		$new_template = locate_template( array( 'front-page.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}
	return $template;
}

add_filter('pre_get_posts', 'hc_alter_main_loop');
function hc_alter_main_loop($query){
	if ( !$query->is_main_query() || $query->is_admin ) return;
	$queried = $query->query;
	// 'position' gets set on all bio pages
	if ( isset($queried['position']) ){
		$query->set('showposts', -1);
	}
}

add_filter('wp_title', 'set_title_for_front', 99);
function set_title_for_front($title){
	if (is_category('special-announcement')) return 'Post Playhouse';
	return $title;
}

//make excerpt display a link to the article
function new_excerpt_more($more) {
       global $post;
	return ' <a href="'.get_post_permalink($post->ID).'" >...Read More</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function new_excerpt_length() {
	return 20;
}
add_filter('excerpt_length', 'new_excerpt_length');


/**
 * Filter the content of bios to include featured thumbnail
**/
function bio_add_thumbnail($html){ //NOTE: filter added in loop.php only for Bio entries. Just defined here.
	//Get the featured image and retrieve thumbnail
	if(has_post_thumbnail( $post->ID )){
		$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'thumbnail');
		$large_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'large');
		//Create necessary HTML and add to beginning of post.
		$html= '<div class="bio_image"><a href="'.$large_image[0].'"><img src="'.$thumb_image[0].'" class="alignleft"></a></div>'.$html;
		}
	return $html;
}

function highest_season_year(){
	static $highest_season_year = null;
	if ($highest_season_year != null) return $highest_season_year;
	
	$highest_season_year = get_terms('season_year',array('orderby' => 'name', 'order' => 'DESC', 'number' => 1));
	$highest_season_year = $highest_season_year[0]->name;

	return $highest_season_year;
}

/* On production archive pages, we only want past seasons. here is how we accomplish that. */
function remove_current_shows_from_production_archive( $query ) {
    //Don't run in admin or on queries OTHER than the main (very first) query
    if ( is_admin() || ! $query->is_main_query() )
        return;

    //Are we on a production archive page?
    if ( is_post_type_archive( 'production' ) ) {
        //Get all posts that are NOT IN highest season_year
        $query->set( 'tax_query', array(array(
			'taxonomy' => 'season_year'
			,'field' => 'slug'
			,'terms' => highest_season_year()
			,'operator' => 'NOT IN'
		)));
        return;
    }
}
add_action( 'pre_get_posts', 'remove_current_shows_from_production_archive', 1 );

