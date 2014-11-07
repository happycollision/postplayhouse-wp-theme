<?php
/*
Plugin Name: Production Photo Post-Type
Plugin URI:
Description: Allows users to add/remove production photo entries.
Author: Don Denton
Version: 1.0
Author URI: http://www.ddphotodesign.com
*/

add_action('init', 'production_photo_init');
function production_photo_init() 
{
  $labels = array(
    'name' => _x('Production Photos', 'post type general name'),
    'singular_name' => _x('Production Photo Gallery', 'post type singular name'),
    'add_new' => _x('Add New', 'Production Photo Gallery'),
    'add_new_item' => __('Add New Production Photo Gallery'),
    'edit_item' => __('Edit Production Photo Gallery'),
    'new_item' => __('New Production Photo Gallery'),
    'view_item' => __('View Production Photo Gallery'),
    'search_items' => __('Search Production Photos'),
    'not_found' =>  __('No production photos found'),
    'not_found_in_trash' => __('No production photos found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug'=>'production-photos'),
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail'),
    'has_archive' => 'production-photos'
  ); 
  register_post_type('production_photo',$args);
}

//add filter to insure the text Production Photo, or production photo, is displayed when user updates a production photo 
add_filter('post_updated_messages', 'production_photo_updated_messages');
function production_photo_updated_messages( $messages ) {

  $messages['production photo'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Production Photo updated. <a href="%s">View Production Photo</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Production Photo updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('production photo restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Production Photo published. <a href="%s">View Production Photo</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Production Photo saved.'),
    8 => sprintf( __('Production Photo submitted. <a target="_blank" href="%s">Preview production photo</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Production Photo scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Production Photo</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Production Photo draft updated. <a target="_blank" href="%s">Preview Production Photo</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//hook into the init action and call create_production_photo_taxonomies when it fires
add_action( 'init', 'create_production_photo_taxonomies', 0 );

//create two taxonomies, positions and season years for the post type "production photo"
function create_production_photo_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Season Years', 'taxonomy general name' ),
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

    if(!taxonomy_exists('photo_year')){
		register_taxonomy('photo_year','production_photo',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'season_gallery' ),
		));
	}
}


/*
 *
 Now for some sweet stuff. Making custom fields user-friendly!
 *
 */
add_action("admin_init", "production_photo_meta_init");
 
function production_photo_meta_init(){
  //format: add_meta_box( $id, $title, $callback, $page, $context, $priority );
  add_meta_box("fake_photo_meta", "How to Create Photo Galleries", "fake_photo_meta", "production_photo", "normal", "low");
}
 
function fake_photo_meta() {
  ?>
	<p>1. Title this page the same as the show for which you are adding photos.</p>
    <p>2. Make sure you give the same season year (on the right) as the production you are referencing.</p>
    <p>3. Click the add photo button above the editing area, and upload the photos from your computer. Don't click the button to "insert into post". Just save them.</p>
    <p>4. Add the text <code>[gallery link="file"]</code> to the editing area.</p>
    <p>5. Publish/Update. (Next time you visit this screen, you'll see a blue box representing the gallery you've just created. Click on it once to see the edit and delete buttons appear on it. Then, click on the edit button that looks like a photo to tweak any settings you like.)</p>
    <p>6. Set the featured image on the right-hand side of the screen. Just click the blue link to "Set featured image", pick the one you want (either from your computer or from the media library tabs at the top of the pop-up window), and click the button that says "Set featured image".</p>
  <?php
}

?>
