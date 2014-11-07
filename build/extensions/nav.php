<div class="nav-menu">
<ul>
   	<li class="page_item <?php if(is_front_page()){echo 'current-menu-item';}?>"><a href="<?php bloginfo('url'); ?>">Home</a></li>
<?php
//find the biggest season_year published and link to that in "Productions" nav link
$post_type = 'production';
$tax = 'season_year';
$tax_terms = get_terms($tax);
if ($tax_terms) {
  foreach ($tax_terms  as $tax_term) {
    $args=array(
      'post_type' => $post_type,
      "$tax" => $tax_term->slug,
      'post_status' => 'publish',
      'posts_per_page' => -1,
    );

    $my_query = null;
    $my_query = new WP_Query($args);
	$biggest_year = '1';
    if( $my_query->have_posts() && $tax_term->name > $biggest_year ) {$biggest_year = $tax_term->name;}
    wp_reset_query();
  }
}

//Set up a boolean for determining which url structures to use below
$pretty_url_on = get_option('permalink_structure')=='/%postname%/' ? TRUE : FALSE;

if($pretty_url_on){
	$urls = array(
		'board' => '/people/board'
		,'staff'=> '/people/staff'
		,'castcrew' => '/people/castcrew/?bio_year='
		,'musicians' => '/people/musicians/?bio_year='
		,'production_photo' => '/production-photos'
		,'productions' => '/season_year/'
		,'past_productions' => '/past-productions'
	);
}else{
	$urls = array(
		'board' => '/?position=board'
		,'staff'=> '/?position=staff'
		,'castcrew' => '/?position=castcrew&bio_year='
		,'musicians' => '/?position=musicians&bio_year='
		,'production_photo' => '/?post_type=production_photo'
		,'productions' => '/?season_year='
		,'past_productions' => '/?post_type=production'
	);
}

?>
   	<?php //set up class for Productions links (easier to read broken out up here)
   		if(get_post_type()=='production'){
   			$pro_class = 'current_page_ancestor';
	   		if(is_tax('season_year',"$biggest_year")){
		   		$now_pro_class = 'current_page_item';
		   		$past_pro_class = '';
	   		}else{
	   			$now_pro_class = 'current_page_ancestor';
	   			$past_pro_class = 'current_page_item';
	   		}
	   	}
	   	
   	
   	?>
   	<li class="page_item page_item_has_children <?php echo $pro_class; ?>">
   		<a href="<?php bloginfo('url'); echo $urls['productions'].$biggest_year; ?>">Productions</a>
   		<ul class="children">
		   	<li class="page_item <?php echo $now_pro_class; ?>">
		   		<a href="<?php bloginfo('url'); echo $urls['productions'].$biggest_year; ?>">The <?php echo $biggest_year; ?> Season</a>
		   	</li>
   			<li class="page_item <?php echo $past_pro_class; ?>">
   				<a href="<?php bloginfo('url'); echo $urls['past_productions'];?>">Past Productions</a>
   			</li>
   		</ul>
    </li>
	<?php 
		wp_list_pages(array(
			'title_li' => '',
			'include' => get_page_by_title('calendar')->ID,
			'menu_class' => '',
		));
	?>
	<?php 
		wp_list_pages(array(
			'title_li' => '',
			'include' => get_page_by_title('gift cards')->ID,
			'menu_class' => '',
		));
	?>


<?php
//find the biggest bio_year published and link to that in "Productions" nav link
$post_type = 'bio';
$tax = 'bio_year';
$tax_terms = get_terms($tax);
if ($tax_terms) {
  foreach ($tax_terms  as $tax_term) {
    $args=array(
      'post_type' => $post_type,
      "$tax" => $tax_term->slug,
      'post_status' => 'publish',
      'posts_per_page' => -1//,
      //'caller_get_posts'=> 1
    );

    $my_query = null;
    $my_query = new WP_Query($args);
	$biggest_year = '1';
    if( $my_query->have_posts() && $tax_term->name > $biggest_year ) {$biggest_year = $tax_term->name;}
    wp_reset_query();
  }
}

?>
    <li class="page_item page_item_has_children <?php if(get_post_type()=='bio') echo 'current-menu-ancestor'; ?>"><a href="<?php bloginfo('url'); echo $urls['castcrew'].$biggest_year; ?>">Who's Who</a>
    	<ul class="children">
        	<li class="page_item <?php if($wp_query->query['position']=='castcrew')echo 'current-menu-item'; ?>"><a href="<?php bloginfo('url'); echo $urls['castcrew'].$biggest_year; ?>">Cast/Crew</a></li>
        	<li class="page_item <?php if($wp_query->query['position']=='musicians')echo 'current-menu-item'; ?>"><a href="<?php bloginfo('url'); echo $urls['musicians'].$biggest_year; ?>">Musicians</a></li>
        	<li class="page_item <?php if($wp_query->query['position']=='staff')echo 'current-menu-item'; ?>"><a href="<?php bloginfo('url'); echo $urls['staff'];?>">Staff</a></li>
        	<li class="page_item <?php if($wp_query->query['position']=='board')echo 'current-menu-item'; ?>"><a href="<?php bloginfo('url'); echo $urls['board'];?>">Board</a></li>
        </ul>
    </li>
    <?php
		wp_list_pages(array(
			'title_li' => '',
			'menu_class' => '',
			'depth' => 1,
			'exclude_tree' => get_page_by_title('hidden')->ID,
			'exclude' =>
				get_page_by_title('calendar')->ID.','.
				get_page_by_title('about')->ID.','.
				get_page_by_title('tickets')->ID.','.
				get_page_by_title('gift cards')->ID.','.
				get_page_by_title('front page')->ID,
			'sort_column' => 'menu_order'
			
		));
	?>
    </li>

    <li class="page_item <?php if ($wp_query->query['post_type'] == 'production_photo') echo 'current_page_ancestor';?>"><a href="<?php bloginfo('url'); echo $urls['production_photo'];?>">Photos</a></li>

    <li class="page_item page_item_has_children <?php if (strstr($wp_query->query['pagename'],'about/'))echo 'current_page_ancestor';?>"><a>About</a>
    	<ul class="children">
			<?php 
                wp_list_pages(array(
                    'title_li' => '',
                    'child_of' => get_page_by_title('about')->ID,
                    'menu_class' => '',
                    'depth' => 1
                ));
            ?>
        </ul>
    </li>

</ul>
</div>