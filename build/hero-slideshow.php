<div id="hero-slideshow">
<?php
	$slides = get_slideshow_slides();
	$slides_json = htmlentities(get_slideshow_slides('json'));
	foreach ($slides as $i => $slide) {
		?>
			<a class="slide <?php if($i==0)echo 'active';?>" href="<?php echo $slide['link']; ?>">
				<img src="<?php echo $slide['image']; ?>" alt="">
				<div class="title"><?php echo $slide['title']; ?></div>
				<div class="caption"><?php echo $slide['caption']; ?></div>
			</a>
		<?php
		//break; // The loop above will only fire once. Convenient if it ever needs to be changed.
	}
?>
</div>

<span id="hero-data" data-slides="<?php echo $slides_json; ?>"></span>
