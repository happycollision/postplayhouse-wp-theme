<style>
#slider {
	background:url(<?php bloginfo('template_url');?>/lib/nivo_slider/style-pack/loading.gif) no-repeat 50% 50%; 
	position:relative;
	float:right;
}
#slider_container{
	background:#000;
	width:400px;
	height:200px;
	float:right;
	overflow:hidden;
	position:relative;
	z-index:1000;
}
#slider img {
	position:absolute;
	top:0px;
	left:0px;
	display:none;
}
#slider_cover{
	background:url(<?php bloginfo('template_url');?>/images/slideshow_cover.png) top left no-repeat;
	position:absolute;
	top:0;
	left:0;
	height:200px;
	width:400px;
}
</style>
	<div id="slider_container"><!--<div id="slider_cover"></div>-->
	<div id="slider">
		<?php $playhouse_slideshow = new WP_Query('post_type=slide&posts_per_page=-1&orderby=rand'/*&cat=3'*/);?>
		<?php while ($playhouse_slideshow->have_posts()) : $playhouse_slideshow->the_post(); ?>
        <?php 
			unset($slide_image);
			if(has_post_thumbnail( $post->ID )){
				//$slide_image = get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
				//$slide_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'post-thumbnail');
				$slide_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'Slideshow');//based on size created by additional image sizes plugin

				//print_r($slide_image);
		} ?>
		<?php $values = get_post_custom_values("gallery_image");?> 
		<?php if(isset(/*$values[0]*/$slide_image))
		{?>
	<img src="<?php echo /*$values[0];*/ $slide_image[0]; ?>" alt="<?php the_title(); ?>" title="<strong><?php the_title();?></strong> <?php echo get_the_excerpt();?>"/> 
		<?php }?>
		<?php endwhile;?>
	</div><!--slider-->
    </div><!--slider_container-->