<?php 
get_header(); 
?>
<h1 class="page-title">Production Photos</h1>
<?php
    if( have_posts() ) : while(have_posts()) : the_post();
    
    	//Since each production should have only one season year, this is just to prevent bugs introduced by user error if two season years are attached.
		$terms = get_the_terms( $post->ID, 'photo_year');
		foreach($terms as $term){
			$this_season_year = $term->name;
			if($this_season_year < $term->name) $this_season_year = $term->name;
		}

		//Get image url of featured image ready to go.
		if(has_post_thumbnail()){
			$featured_image_url_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , 'Slideshow');
			$featured_image_url = $featured_image_url_array[0];
		}else{
			$featured_image_url = FALSE;
		}

?>
<h2 class="production_photo_archive" <?php if($featured_image_url) echo "style=\"background-image:url('$featured_image_url')\"";?> ><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?><span class="smaller"><?php echo $this_season_year;  ?></span></a></h2>
<?php //the_excerpt(); ?>


<?php endwhile; ?>
<div class="anchor"></div>
<?php endif; ?>
<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older articles', 'twentyten' ), $wp_query->max_num_pages ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer articles <span class="meta-nav">&rarr;</span>', 'twentyten' ), $wp_query->max_num_pages ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
<?php get_footer();?>