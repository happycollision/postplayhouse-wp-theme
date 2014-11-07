<?php
/**
 * The template for displaying various Taxonomy Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header(); 
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
if($term->taxonomy!='season_year'){
	query_posts(array_merge(array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
			), $wp_query->query));
}else{
	query_posts(array_merge(array(
			'posts_per_page' => -1,
			), $wp_query->query));
}


?>

		<div id="container">
			<div id="content" role="main">

				<h1 class="page-title"><?php 
					if($term->taxonomy=='season_year'&&$term->description==NULL)$term->description = 'Summer of '.$term->name.' Productions';
					if($term->taxonomy=='bio_year'&&$term->description==NULL)$term->description = 'Who\'s Who at Post in '.$term->name;
					if($term->description==NULL)$term->description=$term->name;

					if(isset($_GET['bio_year']))$term->description .= ' '.$_GET['bio_year'];


					printf( __( '%s', 'twentyten' ), '<span>' . $term->description . '</span>' );
				?></h1><div class="after_h1"></div>
                
				<?php

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				if(get_post_type()=='production'){
					get_template_part( 'loop', 'productions' );
				}else{
					get_template_part( 'loop', 'category' );
				}
				?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
