<?php
if (!function_exists('hc_tab')){ function hc_tab($indent_amount = 0){
	$html = "\n";
	$i = 0;
	while($i++ < $indent_amount){
		$html .= '     ';
	}
	return $html;
}}
if (!function_exists('start_day')){ function start_day($getdate, $day_class = ''){
	//Set the class for td
	$day_class .= ( $getdate['wday'] == 0 || $getdate['wday'] == 6 ) ? ' weekend ' : ' weekday ';
	$day_class .= strtolower($getdate['weekday']) . ' ';
	
	//Add class if date has passed already
	if ( $getdate['yday'] < getdate(time())['yday'] ) {
		$day_class .= ' past ';
	}
	
	//Set name of day and change "Monday" to "Mon"
	$day_name = $getdate['weekday'];
	//if($day_name=="Monday") $day_name = 'Mon';
	
	$html = 
		hc_tab(3) . '<td class="day '.$day_class.'" valign="top">' .
		hc_tab(4) . '<span class="day_name">' . $day_name . '</span>' .
		hc_tab(4) . '<span class="month_name">' . $getdate['month'] . '</span>' .
		hc_tab(4) . '<span class="mday">' . $getdate['mday'] . '</span>';
	
	return $html;
}}
if (!function_exists('pad_back_of_week')){ function pad_back_of_week($getdate){
	$html = '';
	//figure out how many days we need to pad
	$days = 6 - $getdate['wday'];
	
	//Create array of $getdates for next days of the week
	$getdates = array();
	while($days > 0){
		$getdates[] = getdate($getdate[0]+($days*24*60*60));
		$days--;
	}
	$getdates = array_reverse($getdates);
	
	//loop through array of $getdates to pad each day
	foreach($getdates as $getdate){
		$html .= start_day($getdate, ' padding ');
		$html .= hc_tab(3) . '</td><!--day-->';
	}
	
	//end the week
	if(count($getdates) > 0){
		$html .= hc_tab(2) . '</tr><!--week-->';
	}
	
	return $html;
}}
if (!function_exists('pad_front_of_week')){ function pad_front_of_week($getdate){
	$html = hc_tab(2) . '<tr class="week">';
	//figure out how many days we need to pad
	$days = $getdate['wday'];
	
	//Create array of $getdates for previous days of the week
	$getdates = array();
	while($days > 0){
		$getdates[] = getdate($getdate[0]-($days*24*60*60));
		$days--;
	}

	//loop through array of $getdates to pad each day
	foreach($getdates as $getdate){
		$html .= start_day($getdate, ' padding ');
		$html .= hc_tab(3) . '</td><!--day-->';
	}

	return $html;
}}
if (!function_exists('first_of_month')){ function first_of_month($getdate){
	//announce the new month
	if($getdate['year'] != date('Y')){
		$other_year = ' ' . $getdate['year'];
	}else{
		$other_year = '';
	}
	$html = 
		hc_tab(1) . '<h3 class="month_name">' . $getdate['month'] . $other_year . '</h3>' . 
		hc_tab(1) .'<table class="month" cellpadding="0" cellspacing="0">';
	
	//pad the beginning of the week
	$html .= pad_front_of_week($getdate);
	
	return $html;
}}
if (!function_exists('fill_date')){ function fill_date($events_array){
	global $extra_production_info;
	//ddprint($events_array);
	$html = "";
	foreach($events_array as $showtime){
		if(! isset($posts[$showtime->production_id])){
			$posts[$showtime->production_id] = get_post($showtime->production_id);
			setup_postdata($posts[$showtime->production_id]);
		}
		
		//Wrapper
		$html .=  hc_tab(5) . '<div class="showing">'; 
		//Title
		$html .=  hc_tab(6) . '<span class="show_title">' . $posts[$showtime->production_id]->post_title . '</span>';
		//Time
		$html .=  hc_tab(6) . '<span class="show_time">';
			
			//If Description
			if($showtime->description != false){
				$html .= 
					'<a href="#' .
					$showtime->production_id . '_' . str_replace([' ',','],'_',$showtime->performance_type) .
					'" title="' . $showtime->description . '">' .
					$showtime->performance_type . 
					'</a>' .
					': ';
				$extra_production_info[$showtime->production_id . '_' . $showtime->performance_type] = array($showtime, $posts[$showtime->production_id]);
			}else{
				$html .= 
					$showtime->performance_type . ': ';
			}
			
		$html .= HCProduction::humanize_date_time($showtime->time) . '</span>';


		$html .=  hc_tab(5) . '</div><!--showing-->';
	}
	
	return $html;
}}

function make_hc_calendar(){
	global $extra_production_info;
	$html = '';
	//get the calendar dates and remove the dates before the first of the month
	$cal_dates = HCProductionDates::setup_calendar();
	//ddprint($cal_dates);
	if($cal_dates == false){
		$html = "<h2 style=\"text-align:center\">Schedule Not Yet Released</h2> \n
		<p style=\"width:50%;margin:auto\">We are still working out the schedule for the upcoming summer season. We'll be sure to update this page as soon as we have everything finalized.</p>";
		return $html;
	}
	foreach($cal_dates as $date => $maybe_array){
		$getdate = getdate(strtotime($date));
		if($getdate['mday'] != '1'){
			unset($cal_dates[$date]);
		}else{
			break;
		}
	}
	
	
	
	
	
	
	
	//
	//
	//
	//
	// Make the calendar using all the functions above
	//
	//
	//
	//
	
	$html = '<div class="calendar">';
	$i = 0;
	$count = count($cal_dates);


	foreach($cal_dates as $date => $maybe_array){
		$i++;
		$first_run = ($i == 1) ? true : false;
		$getdate = getdate(strtotime($date));
		$getdate_tomorrow = getdate(strtotime('tomorrow',strtotime($date)));
		
		//If it is the first of the month, do first_of_month()
		if($getdate['mday']==1){
			$html .= first_of_month($getdate, $first_run);
		}
		
		//Are we in a new week, other than the first week?
		if($getdate['wday']==0 && $getdate['mday'] > 1){
			$html .= hc_tab(2) . '<tr class="week">';
		}
		
		//If we have an array, we have work to do.
		if( ! is_array($maybe_array)){
			//Nice and easy
			$html .= start_day($getdate, 'dark');
			$html .= hc_tab(4) . '<div class="day_content"> &nbsp; </div><!--day_content-->';
		}else{
			$html .= start_day($getdate);
			//Now comes all the fun stuff:
			
			$html .= hc_tab(4) . '<div class="day_content">' . fill_date($maybe_array) . hc_tab(4) . '</div><!--day_content-->';
			
			
		}
		
		$html .= hc_tab(3) . '</td><!--day-->';
		
		//Are we at the end of the week?
		if($getdate['wday']==6){
			$html .= hc_tab(2) . '</tr><!--week-->';
		}
		
		//Are we at the end of the month or the data?
		if($getdate['month'] != $getdate_tomorrow['month'] || $count == $i){ 
			//pad the rest of the week
			$html .= pad_back_of_week($getdate);
			
			//end the last month
			$html .= hc_tab(1) . '<div class="anchor"></div></table><!--month-->';
		}
		//if ($i==125) return $html . '</div><!--calendar-->'; //testing
	}
	$html .= hc_tab(0) . '</div><!--calendar-->';
	
	
	
	if(count($extra_production_info) > 0){
		foreach ($extra_production_info as $production){
			$output = '<div class="extra_show_info" id="' . $production[0]->production_id . '_' . str_replace([' ',','],'_',$production[0]->performance_type) . '">';
			$output .= '<span class="show_title">' . $production[1]->post_title . '</span> ' . $production[0]->performance_type . ': <br />' . $production[0]->description;
			$output .= '</div>';
			$html .= $output;
		}
	}
	
	return $html;
}