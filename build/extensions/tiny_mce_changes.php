<?php
/**
 * TinyMCE customization
 * via http://blog.estherswhite.net/2009/11/customizing-tinymce/
 */
 
//add styles
function childtheme_mce_css($wp) {
return $wp .= ',' . get_bloginfo('stylesheet_directory') . '/css/mce.css';
}
//add_filter( 'mce_css', 'childtheme_mce_css' );

//customize buttons
function childtheme_mce_btns2($orig) {
return array('formatselect', 'styleselect', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'charmap', '|', 'undo', 'redo', 'wp_help', 'mymenubutton' );
}
add_filter( 'mce_buttons_2', 'childtheme_mce_btns2', 999 );

//specify styles instead of import
function childtheme_tiny_mce_before_init( $init_array ) {
$init_array['theme_advanced_styles'] = 
			"Show Title=show_title;" .// add ";" inside (left of double quote) string and concatonnate if adding more styles
			"Song Title=song_title;" .
			"Bio Position/Role=bio_role;" .
			"NOTICE=notice"
			;
$init_array['theme_advanced_blockformats'] = "p,h5,h6";
			
			
return $init_array;
}
add_filter( 'tiny_mce_before_init', 'childtheme_tiny_mce_before_init' );

//change css for visual editor!!
//add_filter('mce_css', 'my_editor_style');
function my_editor_style($url) {

  if ( !empty($url) )
    $url .= ',';

  // Change the path here if using sub-directory
  $url .= trailingslashit( get_stylesheet_directory_uri() ) . 'editor-style.css';

  return $url;
  
}
