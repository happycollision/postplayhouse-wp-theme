<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
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
<?php while ( have_posts() ) : the_post(); ?>
<?php // This code removes people from staff if they have a year associated with them that is NOT the current year

if(is_tax('position','staff')){

$this_year = date("Y"); //probably the best method, in case we have any future years accidentlly in the database.

	if(has_term( '', 'bio_year' )){
		
		if(has_term($this_year, 'bio_year')){
			// we're good.
		}else{
			// we're not good. Skip this dude.
			continue;
		}
		
	}
}

?>


		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if(is_page()&& !isset($first_article)) {?>
			<h1 class="page-title"><?php the_title(); ?></h1><div class="after_h1"></div>
            
<?php 
// use wp_list_pages to display parent and all child pages all generations (a tree with parent)
//dont display this menu on hidden pages
if($post->ID != get_page_by_title('hidden')->ID){

	if(!$post->post_parent||
		$post->post_parent==get_page_by_title('about')->ID||
		$post->post_parent==get_page_by_title('tickets')->ID){
			$parent = $post->ID;
	}else{
		$parent=$post->post_parent;
	}
	$args=array(
	  'child_of' => $parent
	);
	$pages = get_pages($args); 

	if ($pages) {
		$pageids = array();
		foreach ($pages as $page) {
			$pageids[]= $page->ID;
		}
		
		$args=array(
		'title_li' => '',
		'include' =>  $parent . ',' . implode(",", $pageids),
		'echo' =>	  0,
		'sort_column' => 'menu_order'
		);
		if(wp_list_pages($args)){echo '<ul class="page_tree">'.wp_list_pages($args).'</ul>';}
	}
}
?>
			
			<?php $first_article=1;}else{?>
            <?php if($wp_query->query_vars['post_type']=='production_photo'){echo '<h1 class="page-title">Production Photos</h1><div class="after_h1"></div>';}?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php if(!is_single()){?>class="hover"<?php }?>><?php the_title(); ?></a></h2>
			<?php }?>

			<div class="entry-meta">
				<?php if(is_home())the_date('F j, Y'); ?>
			</div><!-- .entry-meta -->

	<?php if( get_post_type() == 'bio' ) : //DDPhoto Only display excerpts for archives and search... not bios.?>
			<div class="entry-content">
				<?php add_filter('the_content','bio_add_thumbnail'); the_content(); ?>
			</div><!-- .entry-content -->
	<?php elseif ( (is_archive() || is_search()) && !is_category('front') ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content">
            	<?php if (empty($post->post_excerpt)||is_single()){
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) );
				}else{
					the_excerpt();?>
                    <br /><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">Read the full story...</a>
				<?php }?>
				<?php /*DDPhoto*/ wp_link_pages( array( 'before' => '<div class="page-link bottom">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
				<?php /*DDPhoto*/wp_link_pages( array( 'before' => '<div class="page-link top">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

			<div class="entry-utility">
				<?php if ( count( get_the_category() ) /*DDPhoto-->*/ && 1==2) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->


<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older articles', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer articles <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
