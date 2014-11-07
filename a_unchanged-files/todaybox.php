<style>
div#todayBox{
	height:200px;
	width:200px;
	background-color:#fff;
	position:relative;
	float:left;
	border:10px solid #070b34;
	margin-top:26px;
}
#todayMenu{
	position:absolute;
	height:200px;
	width:200px;
	left:-9999em;
	background:rgb(256, 256, 256);
	background:rgba(256, 256, 256, 0.7);
	text-shadow:0px 0px 12px #fff;
}
#todayMenu li a{
	color:#111;
	font-size:2em;
	font-family:Arial, Helvetica, sans-serif;
	font-weight:bold;
	text-decoration:none;
	display:block;
	margin:8px auto;
	margin-bottom:24px;
	padding:4px;
	text-align:center;
	line-height:1em;
}
#todayMenu li a:hover{
	background-color:#666;
	background-color:rgba(000,000,000, 0.7);
	color:#fff;
	text-shadow:none;
}
div#todayBox:hover ul#todayMenu {
	left:auto;
}
#todayBox .pre_placard{
	position:absolute;
	top:-36px;
	width:200px;
}
#todayBox .placard{
	position:relative;
	margin:auto;
	width:6em;
	text-align:center;
}

</style>
<?php 	$current_day = date('j');
		$current_year = date('Y');
		$playhouse_todaybox = new WP_Query("post_type=todaybox&showposts=1&day={$current_day}&year={$current_year}");
		if($playhouse_todaybox->have_posts()): while ($playhouse_todaybox->have_posts()) : $playhouse_todaybox->the_post(); 

        
			if(has_post_thumbnail( $post->ID )){
				$todaybox_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'Today Box');//based on size created by additional image sizes plugin
				$todaybox_image[0];
?>
                <div id="todayBox" style="background-image:url(<?php echo $todaybox_image[0];?>); background-repeat:no-repeat">
                	<span class="pre_placard"><span class="placard">Up Next</span></span>
                	<ul id="todayMenu">
                    	<li><a href="<?php echo get_permalink(get_page_by_title('calendar')->ID);?>">Performance Schedule</a></li>
                    	<li><a href="<?php echo get_permalink(get_page_by_title('tickets')->ID);?>">Purchase Tickets</a></li>
                    </ul>
                    
                </div><!--todayBox-->
<?php }	endwhile;
		else: 
			$four_days = strtotime("+4 days");
			$four_days = date('Y-m-d H:i:s',$four_days);
			$result = $wpdb->get_results("
				SELECT ID from $wpdb->posts wposts
				WHERE post_type='todaybox'
				AND post_status='future'
				AND post_date<'{$four_days}'
				ORDER BY post_date DESC;
			");
			if($result){
				foreach($result as $today_box){
					$today_box_id = $today_box->ID;
				}
				$todaybox_image = wp_get_attachment_image_src(get_post_thumbnail_id($today_box_id),'Today Box');//based on size created by additional image sizes plugin
				$todaybox_image[0];
				?>
					<div id="todayBox" style="background-image:url(<?php echo $todaybox_image[0];?>); background-repeat:no-repeat">
						<ul id="todayMenu">
							<li><a href="<?php echo get_permalink(get_page_by_title('calendar')->ID);?>">Performance Schedule</a></li>
							<li><a href="<?php echo get_permalink(get_page_by_title('tickets')->ID);?>">Purchase Tickets</a></li>
						</ul>
						
					</div><!--todayBox-->
            
		<?php }else{ ?>
					<div id="todayBox">
no show here
					</div><!--todayBox-->
<?php	}
		endif;
		?>
