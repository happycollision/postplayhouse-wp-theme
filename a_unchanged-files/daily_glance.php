<?php $upcoming = HCProductionDates::setup_preview_box();
	if($upcoming != false):
?>
<style>
	#daily_glance{
		background-color: rgba(255,255,255,0.14);
		padding:8px;
		overflow: hidden;
	}
	#daily_glance .daily_glance_day{
		position: relative;
	}
	#daily_glance .daily_glance_day .title{
		font-size: 1.8em;
		background-color: rgba(0,0,0,0.5);
		padding:8px;
		color:#aaa;
		margin:4px auto;
	}
	#daily_glance .sponsor_image{
		width:100px;
		float:right;
		font-size: 0.7em;
		padding:3px;
		margin:0;
		background-color: none;
		text-align: center;
	}
	#daily_glance .showing_time{
		padding: 4px;
		margin:4px auto;
		background-color: rgba(255,255,255,0.5);
	}
	#daily_glance h3{
		font-weight: bold;
		margin:8px auto;
		text-align: center;
	}
	#daily_glance .image_holder{
		height: 180px;
	}
	#daily_glance .image_holder img{
		max-height: 174px;
	}
	#daily_glance .hidden {
		display: none;
	}
	#daily_glance *:hover > .hidden{
		display:block;
		position: absolute;
		left:0;
		background: rgba(0,0,0,0.9);
		width:98%;
		padding:1%;
		border:2px solid #000;
		color:#aaa;
		z-index: 5;
	}
	#daily_glance .daily_glance_button{
		background-color: rgba(255,255,255,0.3);
		padding:2px;
		border:1px solid rgba(0,0,0,0.5);
		border-radius: 3px;
		border-top-color: rgba(255,255,255,0.5);
		border-left-color: rgba(255,255,255,0.5);
		cursor: default;
		position: relative;
		top:-6px;
	}
	#daily_glance .daily_glance_button:active{
		background-color: rgba(255,255,255,0.1);
		border-color:rgba(0,0,0,0.5);
		border-bottom-color: rgba(255,255,255,0.5);
		border-right-color: rgba(255,255,255,0.5);
	}
	#daily_glance h3 a{
		color:inherit;
	}
	.no-js #daily_glance .daily_glance_button{
		display:none;
	}
	.daily_glance_day{
		display:none;
	}
	.daily_glance_day.first{
		display: block;
	}
</style>
<?php
$temp_post = $post;
$today = date('Y-m-d');
$tomorrow = date('Y-m-d',strtotime('tomorrow'));

?><div id="daily_glance"><?php
$i = 0;
foreach ($upcoming as $date => $object_array){
	if($date == $today){
		$date_string = "Today";
	}elseif($date == $tomorrow){
		$date_string = "Tomorrow";
	}else{
		$date_string = date('l, F jS',strtotime($date));
	}
	
	if(count($upcoming) > 1){
		if($i == 0){
			$buttons = '<span class="daily_glance_button alignright">Next</span>';
			$day_class = ' first';
		}else{
			$buttons = '<span class="daily_glance_button alignleft">Previous</span><a class="alignright" href="'.
						esc_url( get_permalink( get_page_by_title( 'Calendar' ) ) ).
						'">Full Calendar</a>';
			$day_class = '';
		}
	}else{
		$buttons = '';
		$day_class = ' first';
	}

	?><div class="daily_glance_day<?php echo "$day_class";?>">
	<h3><?php echo $buttons;?>Playing <?php echo $date_string;?></h3>
	<?php
	
	//if the day's performances total less than 2:
	if(count($object_array)<2){
	
		foreach ($object_array as $production_data){
			$post = get_post($production_data->production_id);
			setup_postdata($post);
			?>
	
<div class="showing single">
	<div class="title"><?php echo $post->post_title;?></div>
	<span class="alignleft"><?php ProductionInfo::echo_image($post->ID);?></span>
	<?php 
	    if (class_exists('MultiPostThumbnails')
	    && MultiPostThumbnails::has_post_thumbnail('production', 'sponsor-image')) {
		    $thumbnail =  MultiPostThumbnails::get_post_thumbnail_url('production', 'sponsor-image',$post->ID,'sponsor-sized');
		    ?>	<div class="sponsor_image">Sponsored By:
		    <img style="max-width:100%" src="<?php echo $thumbnail; ?>" /></div><?php
		}
		echo $post->post_content;
    ?>
    <div class="anchor"></div>
    <div class="showing_time">
    	<?php echo $production_data->performance_type;?> performance at <?php echo date('g:ia',strtotime($production_data->time));?>
    	<?php if ($production_data->description != ''){
	    	echo '<div class="performance_description">' . $production_data->description . '</div>';
    	}?>
    </div>
	
</div><!--showing-->	
			
			<?
		}
		
	}else{ //there are multiple performances for the day
?><table style="width:100%"><tr><?php
		foreach ($object_array as $production_data){
			$post = get_post($production_data->production_id);
			setup_postdata($post);
			?>
	
<td><div class="showing" style="padding:8px;">
	<div class="title"><?php echo $post->post_title;?></div>
	<div class="image_holder"><?php ProductionInfo::echo_image($post->ID);?>
	<div class="hidden">
	<?php 
	    if (class_exists('MultiPostThumbnails')
	    && MultiPostThumbnails::has_post_thumbnail('production', 'sponsor-image')) {
		    $thumbnail =  MultiPostThumbnails::get_post_thumbnail_url('production', 'sponsor-image',$post->ID,'sponsor-sized');
		    ?>	<div class="sponsor_image">Sponsored By:
		    <img style="max-width:100%" src="<?php echo $thumbnail; ?>" /></div><?php
	    }
	    echo $post->post_content;
    ?>
    </div>
	</div><!--image holder-->
	<div class="anchor"></div>

    <div class="showing_time">
    	<?php echo $production_data->performance_type;?> performance at <?php echo date('g:ia',strtotime($production_data->time));?>
    	<?php if ($production_data->description != ''){
	    	echo '<div class="performance_description">' . $production_data->description . '</div>';
    	}?>
    </div>
	
</div></td><!--showing-->	
			
			<?
		}
?></tr></table><?php
	}
	
	
	?></div><!--daily_glance_day--><?php
	if(++$i>1)break;//no more than 2 days, please
}
?></div><!--dailyglance-->
<?php $post = $temp_post;
endif;

