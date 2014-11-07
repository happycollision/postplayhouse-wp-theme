<?php
/*
Plugin Name: Bio Post-Type
Plugin URI:
Description: Allows users to add/remove biography entries.
Author: Don Denton
Version: 1.0
Author URI: http://www.ddphotodesign.com
*/

add_action('init', 'bio_init');
function bio_init() 
{
  $labels = array(
    'name' => _x('Bios', 'post type general name'),
    'singular_name' => _x('Bio', 'post type singular name'),
    'add_new' => _x('Add New', 'Bio'),
    'add_new_item' => __('Add New Bio'),
    'edit_item' => __('Edit Bio'),
    'new_item' => __('New Bio'),
    'view_item' => __('View Bio'),
    'search_items' => __('Search Bios'),
    'not_found' =>  __('No bios found'),
    'not_found_in_trash' => __('No bios found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','thumbnail','editor','revisions')
  ); 
  register_post_type('bio',$args);
}

//add filter to insure the text Bio, or bio, is displayed when user updates a bio 
add_filter('post_updated_messages', 'bio_updated_messages');
function bio_updated_messages( $messages ) {

  $messages['bio'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Bio updated. <a href="%s">View Bio</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Bio updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('bio restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Bio published. <a href="%s">View Bio</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Bio saved.'),
    8 => sprintf( __('Bio submitted. <a target="_blank" href="%s">Preview bio</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Bio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Bio</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Bio draft updated. <a target="_blank" href="%s">Preview Bio</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//hook into the init action and call create_bio_taxonomies when it fires
add_action( 'init', 'create_bio_taxonomies', 0 );

//create two taxonomies, positions and season years for the post type "bio"
function create_bio_taxonomies() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Positions', 'taxonomy general name' ),
    'singular_name' => _x( 'Position', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Positions' ),
    'all_items' => __( 'All Positions' ),
    'parent_item' => __( 'Parent Position' ),
    'parent_item_colon' => __( 'Parent Position:' ),
    'edit_item' => __( 'Edit Position' ), 
    'update_item' => __( 'Update Position' ),
    'add_new_item' => __( 'Add New Position' ),
    'new_item_name' => __( 'New Position Name' ),
  ); 	

  register_taxonomy('position',array('bio'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'people' ),
  ));

  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Cast/Crew Season', 'taxonomy general name' ),
    'singular_name' => _x( 'Season Year', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Season Years' ),
    'popular_items' => __( 'Popular Season Years' ),
    'all_items' => __( 'All Season Years' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Season Year' ), 
    'update_item' => __( 'Update Season Year' ),
    'add_new_item' => __( 'Add New Season Year' ),
    'new_item_name' => __( 'New Season Year' ),
    'separate_items_with_commas' => __( 'Separate season years with commas' ),
    'add_or_remove_items' => __( 'Add or remove season years' ),
    'choose_from_most_used' => __( 'Choose from the most used season years' )
  ); 

    if(!taxonomy_exists('bio_year')){
		register_taxonomy('bio_year','bio',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'bio_year' ),
		));
	}
}
?>
