<?php
	if(have_posts()):
?>
<div id="special-announcements">
<?php	while(have_posts()): the_post(); ?>
	<div <?php post_class(); ?> >
		<h2 class="entry-header entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<div class="entry-summary">
    	<?php the_excerpt('read more');?>
		</div><!-- .entry-summary -->

		<div class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
		</div>
	</div>


<?php endwhile; ?>
</div>
<?php endif; ?>
