<?php

//The Production page info generator
class ProductionInfo {
	public $array_of_years;
	public $year;
	public $message;
	public $date_list;
	public $opening;
	public $closing;
	public $writers;
	public $title;
	
	public static function echo_image($post_id){
		if(has_post_thumbnail( $post_id )){
			$thumb_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'medium');
			$large_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'large');
			//Create necessary HTML and add to beginning of post.
			$html= '<img src="'.$thumb_image[0].'" class="production_image">'.$html;
		}
		echo $html;
	}
	public function image(){
		//Get the featured image and retrieve thumbnail
		global $post;
		self::echo_image($post->ID);
	}
	
	public static function get(){
		$object=new ProductionInfo;
		$object->populate();
		return $object;
	}
	public function populate(){
		global $post;
		//Get the year(s) of production
		$terms = get_the_terms( $post->ID, 'season_year');
		foreach($terms as $term){
			$this->array_of_years[] = $term->name;
			if($this->year < $term->name) $this->year = $term->name;
		}
	
	
		//Timely Message
		$custom = HCProductionDates::get_dates($post->ID);
		if($custom->closing_date!=NULL && strtotime($custom->closing_date)>=strtotime('today')){
			if($custom->opening_date!=NULL){
				if(strtotime($custom->opening_date)==strtotime('today')) $this->message = 'Opens Today!';
				if(strtotime($custom->opening_date)<strtotime('today')) $this->message = 'Now Playing!';
				if(strtotime($custom->closing_date)==strtotime('today')) $this->message .= ' Closes Today!';
				if(strtotime($custom->opening_date)>strtotime('today'))$this->message = 'Opens '.$custom->opening_date;
			}
		}elseif(strtotime($this->year)==strtotime(date("Y"))){
			$this->message = 'Thanks for a great run!';
		}
		
		//Dates
		$this->date_list = '<ul><li>'.str_replace("\n",'</li><li>',$custom->date_list).'</li></ul>';
		$this->opening = $custom->opening_date;
		$this->closing = $custom->closeing_date;
		
		//Create a list of authors and combine properly
		//lyrics
		$lyricists=get_the_terms( $post->ID, 'lyricist');
		if(is_array($lyricists)){
			foreach($lyricists as $lyricist){
				$lyricist_list[] = $lyricist->name;
			}
			$lyricist_list = implode(', ',$lyricist_list);
			if(strpos($lyricist_list, ',')){
				$last_comma = strrpos($lyricist_list, ', ');
				$lyricist_list = substr_replace($lyricist_list,', and ',$last_comma,2);
			}
		}
		//music
		$composers=get_the_terms( $post->ID, 'composer');
		if(is_array($composers)){
			foreach($composers as $composer){
				$composer_list[] = $composer->name;
			}
			$composer_list = implode(', ',$composer_list);
			if(strpos($composer_list, ',')){
				$last_comma = strrpos($composer_list, ', ');
				$composer_list = substr_replace($composer_list,', and ',$last_comma,2);
			}
		}
		//book
		$authors=get_the_terms( $post->ID, 'author');
		if(is_array($authors)){
			foreach($authors as $author){
				$author_list[] = $author->name;
			}
			$author_list = implode(', ',$author_list);
			if(strpos($author_list, ',')){
				$last_comma = strrpos($author_list, ', ');
				$author_list = substr_replace($author_list,', and ',$last_comma,2);
			}
		}
		//combine lists
		//"By ..." Only one list has values or all lists are the same
		if(
			//two blank lists
			($author_list==NULL && $composer_list==NULL && $lyricist_list!=NULL)||
			($author_list==NULL && $composer_list!=NULL && $lyricist_list==NULL)||
			($author_list!=NULL && $composer_list==NULL && $lyricist_list==NULL)||
			//all lists are equal
			($author_list==$composer_list && $composer_list==$lyricist_list && $author_list!=NULL)||
			//two are equal and one is blank
			($author_list != NULL && $author_list==$composer_list && $lyricist_list==NULL)||
			($lyricist_list != NULL && $lyricist_list==$author_list && $composer_list==NULL)||
			($composer_list != NULL && $composer_list==$lyricist_list && $author_list==NULL)
		) {
			$this->writers = 'By '.$author_list.$composer_list.$lyricist_list;
		
		//Music and Lyrics by ... Book by ...
		}elseif($lyricist_list==$composer_list && $lyricist_list != NULL){
			$this->writers = 'Music and Lyrics by ' . $composer_list;
			if($author_list!=NULL){$this->writers .= '<br>Book by ' . $author_list;}
		
		//Book by ... Music by ...
		}elseif($author_list!=NULL && $composer_list!=NULL && $lyricist_list==NULL){
			$this->writers = 'Book by ' . $composer_list . '<br>' . 'Music by ' . $composer_list;
		
		//Music by ... Lyrics by ... Book by ...
		}else{
			if($composer_list!=NULL){$this->writers = 'Music by '.$composer_list;}
			if($lyricist_list!=NULL){
				if($this->writers!=NULL){$this->writers .='<br>';}
				$this->writers .= 'Lyrics by ' . $lyricist_list;
			}
			if($author_list!=NULL){
				if($this->writers!=NULL){$this->writers .='<br>';}
				$this->writers .= 'Book by ' . $author_list;
			}
		}		
	}
}

