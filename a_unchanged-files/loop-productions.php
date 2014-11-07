<?php 
//Get the season year we are dealing with on this page
$this_season_year = get_query_var('season_year');

//If get_query_var returns null/False, then this is a page with a single show... so:
if($this_season_year==FALSE){
	$terms = get_the_terms( $post->ID, 'season_year');
	foreach($terms as $term){
		$this_season_year = $term->name;
		if($this_season_year < $term->name) $this_season_year = $term->name;
	}
}



//Getting the link to the cast Bios

	$args=array(
	  'post_type' => 'bio',
	  'bio_year' => $this_season_year,
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	);
	$my_bios_query = new WP_Query($args);

    if( $my_bios_query->have_posts() ) {
	    $permalink_to_cast_bios = get_bloginfo('url') . '/?bio_year=' . $this_season_year;
	}else{
		$permalink_to_cast_bios = FALSE;
	}
    wp_reset_query();


//Get all the season year production photo titles to later compare with these show titles.

	$args=array(
	  'post_type' => 'production_photo',
	  'photo_year' => $this_season_year,
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




//begin the loop
while ( have_posts() ) : the_post(); ?>

		<?php $production=ProductionInfo::get(); //class definition found in Class_production_info_view.php ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			
			<?php if(is_page()&& !isset($first_article)) { // !FIX (What does this do? ?>
			<h1 class="page-title"><?php //get_taxonomy('season_year'); ?></h1>
			
			<?php $first_article=1;}else{?>
            
			<h2 class="entry-title production_title">
				<!-- <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"> -->
					<?php $production->title = the_title('','',0); echo $production->title;?>
				<!-- </a> -->
			</h2>
            <span class="writers"><?php echo $production->writers; ?></span>
			<?php }?>

			<div class="entry-content">
                <div class="production_image_holder">
                	<?php $production->image();?>
            	    <?php //sponsor image
	                    if (class_exists('MultiPostThumbnails')
	                    && MultiPostThumbnails::has_post_thumbnail('production', 'sponsor-image')) {
	                        //MultiPostThumbnails::the_post_thumbnail('production', 'sponsor-image',$post->ID,'sponsor-sized');
	                        
	                        $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('production','sponsor-image', $post->ID);
	                        $url = wp_get_attachment_image_src( $post_thumbnail_id, 'sponsor-sized' );
	                        echo '<img class="sponsor_image" src="' . $url[0] . '" />';
	                    }
                    ?>
                </div>
            	<div class="production_stats">
                    <div class="running_dates"><?php echo $production->date_list;?></div>
                    <div class="dates_message"><?php echo $production->message;?></div><div class="anchor"></div>
                </div><!--production_stats-->
                <div class="production_content">
                
<?php // Link to cast bios if they exist and link to galleries if they exist ?>
<?php if($permalink_to_cast_bios){ ?>
		<a href="<?php echo $permalink_to_cast_bios;?>" class="photo_gallery">Meet the <?php echo $this_season_year; ?> Cast</a>
<?php } ?>

<?php if(array_key_exists(the_title('','',false), $production_photo_galleries)){ ?>
		<a href="<?php echo $production_photo_galleries[the_title('','',false)];?>" class="photo_gallery">View Photo Gallery</a>
<?php } ?>

					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?>
                </div><!--production_content-->
			</div><!-- .entry-content -->
            </div><!-- post?-->

<?php endwhile; // End the loop. ?>