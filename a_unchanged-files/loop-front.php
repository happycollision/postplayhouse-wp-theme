<?php //pre loop fun with special announcements...
			$special = get_cat_id('Special Announcement');
		if($special):$specials = new WP_Query("cat={$special}");
			if($specials->have_posts()):while($specials->have_posts()):$specials->the_post();
?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php if(!is_single()){?>class="hover"<?php }?>><?php the_title(); ?></a></h2>

			<div class="entry-content">
            	<?php the_content();?>
			</div><!-- .entry-content -->

			<div class="entry-utility">
				<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->
        <div class="anchor"></div>


<?php endwhile; wp_reset_query(); endif; endif; //end of the pre-loop specials ?>

<div class="recent_stories">Recent Stories:</div>

<?php 
			$not_front = get_cat_id('Not on Front');
			query_posts(array_merge(array(
						'showposts' => '6',
						'cat' => "-{$not_front},-{$special}",
						), $wp_query->query));

?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>
<?php 
	/* 	Start up a side loop here that shows any Special Announcements.  
		Keeps track of them to exclude from real loop
	*/ ?>


<?php
	/* Start the Loop.
	 *
	 * In Twenty Ten we use the same loop in multiple contexts.
	 * It is broken into three main parts: when we're displaying
	 * posts that are in the gallery category, when we're displaying
	 * posts in the asides category, and finally all other posts.
	 *
	 * Additionally, we sometimes check for whether we are on an
	 * archive page, a search page, etc., allowing for small differences
	 * in the loop on each template without actually duplicating
	 * the rest of the loop that is shared.
	 *
	 * Without further ado, the loop:
	 */ ?>
<div id="front_page_posts_holder">     
<?php while ( have_posts() ) : the_post(); ?>
<?php if (get_post_meta($post->ID, 'external_link_only', true) != NULL): //check if is external link only and not a full story ?>

		<a href="<?php echo get_post_meta($post->ID, 'external_link_only', true); ?>" title="<?php printf( esc_attr__( 'Link to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="front_page_posts" target="_blank">
        <div id="post-<?php the_ID(); ?>" class="front_page_posts">
        	<?php 
			$post_icon = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'thumbnail');
			if($post_icon[0]){?>
            <img src="<?php echo $post_icon[0];?>" height="100" width="100" /><?php }?>
			<h2 class="entry-title"><?php the_title(); ?></h2>

			<div class="entry-content">
			<?php the_excerpt();
			?>
            <span class="read_more">Click to visit this link.</span>
            <span class="the_date"><?php the_time('n-j-Y');?></span>
			</div><!-- .entry-content -->
			<div class="anchor"></div>
		</div><!-- #post-<?php the_ID(); ?> -->
        </a>

<?php else: //is normal story ?>

		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="front_page_posts">
        <div id="post-<?php the_ID(); ?>" class="front_page_posts">
        	<?php 
			$post_icon = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'thumbnail');
			if($post_icon[0]){?>
            <img src="<?php echo $post_icon[0];?>" height="100" width="100" /><?php }?>
			<h2 class="entry-title"><?php the_title(); ?></h2>

			<div class="entry-content">
			<?php the_excerpt();
			?>
            <span class="read_more">Click to Read More</span>
            <span class="the_date"><?php the_time('n-j-Y');?></span>
			</div><!-- .entry-content -->
			<div class="anchor"></div>
		</div><!-- #post-<?php the_ID(); ?> -->
        </a>

<?php endif; ?>

<?php endwhile; // End the loop.?>
<div class="anchor"></div>
</div><!--front_page_posts_holder-->