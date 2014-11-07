<?php
/*
Plugin Name: Slideshow Post-Type
Plugin URI:
Description: Allows users to add/remove slides from slideshow post-type.
Author: Don Denton
Version: 1.0
Author URI: http://ddphotodesign.com
*/

add_action('init', 'slideshow_post_type_init');
function slideshow_post_type_init() 
{
  $labels = array(
    'name' => _x('Slideshow', 'post type general name'),
    'singular_name' => _x('Slide', 'post type singular name'),
    'add_new' => _x('Add New', 'slide'),
    'add_new_item' => __('Add New Slide'),
    'edit_item' => __('Edit Slide'),
    'new_item' => __('New Slide'),
    'view_item' => __('View Slide'),
    'search_items' => __('Search Slides'),
    'not_found' =>  __('No slides found'),
    'not_found_in_trash' => __('No slides found in Trash'), 
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
    'supports' => array('title','thumbnail','excerpt')
  ); 
  register_post_type('slide',$args);
}

//add filter to insure the text Book, or book, is displayed when user updates a book 
add_filter('post_updated_messages', 'slide_updated_messages');
function slide_updated_messages( $messages ) {

  $messages['slide'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Slide updated. <a href="%s">View slide</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Slide updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slide restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Slide published. <a href="%s">View slide</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Book saved.'),
    8 => sprintf( __('Slide submitted. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Slide draft updated. <a target="_blank" href="%s">Preview slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

add_action("admin_init", "slideshow_meta_init");
function slideshow_meta_init(){
  //format: add_meta_box( $id, $title, $callback, $page, $context, $priority );
  add_meta_box('slideshow_meta', 'Additional Slideshow Information', 'slideshow_meta', 'slide', 'normal', 'low');
}

function slideshow_meta() {
  global $post;
  $meta = get_post_meta($post->ID);
  ?>
  <p class="left"><label>Link:</label> <span class="hint">without <code>http://</code></span><br />
  <input type="text" value="<?php echo $meta['hc_slide_link'][0]; ?>" size="50" name="hc_slide_link">
  </p>
  
  <p class="left"><label>Display Text:</label> <span class="hint">Check this box to lay the title and excerpt text over the image</span><br />
  <input type="checkbox" value="true" <?php if($meta['hc_slide_show_text'][0]) echo 'checked'; ?> size="50" name="hc_slide_show_text">
  </p>
  
  <input type="hidden" value="NotAuto" name="hc_slide_autosave_check" />
  <?php
}

//Now to make sure the ALL the data gets saved:
add_action('save_post', 'hc_slide_save_details');
function hc_slide_save_details(){
  global $post;
  
  if($_POST['hc_slide_autosave_check']){
    //Save the external information fields
    update_post_meta($post->ID, 'hc_slide_link', $_POST['hc_slide_link']);
    update_post_meta($post->ID, 'hc_slide_show_text', $_POST['hc_slide_show_text']);
  }
}
?>