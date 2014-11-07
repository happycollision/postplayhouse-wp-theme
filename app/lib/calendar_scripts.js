(function($){
	$(window).load(function() {
		/* Get the width of the calendar and divide evenly amongst the days. */
		cal_width = $('div.calendar').width();
		
		var calSizer = function(){
			if($(document).width() < 644) {
				$('div.calendar td.day').attr('style', '');
			}else{
				monday_width = cal_width/7-50;
				other_days_width = (cal_width-monday_width)/6;
				$('div.calendar td.day.monday').width(monday_width + 'px')
				$('div.calendar td.day').not('.monday').width(other_days_width + 'px');
			}
		}
		//Make Mondays a little smaller
		calSizer();
		$(window).on('resize',calSizer);
		
		//Scroll to the anchor when hyperlink is clicked
		$('.showing a').on('click', function(e){
			e.preventDefault();
			console.log($(this));
			//grab the hash that gives us the id
			var id_referenced_by_click = $(this).attr('href');
			
			//scroll to that id
			$('html body').animate({
			    scrollTop: ( $(id_referenced_by_click).offset().top - $(window).height()/2 ) + 'px'
			}, 'slow', function(){
				//after scroll ends, flash pertinent info
				$(id_referenced_by_click).animateHighlight('#777',500);
			});
		});
		
		/* Extends jQuery to allow easy flashing of an element. Called above. */
		$.fn.animateHighlight = function(highlightColor, duration) {
   		  var highlightBg = highlightColor || "#FFFF9F";
		    var animateMs = duration || 1500;
		    
		    $clone = this.clone();
		    this.css('position','relative');
		    $clone.css({'background':highlightBg, 'position':'absolute', 'top':0, 'left':0, 'opacity':0, 'margin':0}).appendTo(this);
		    
		    blink = function(callback) {$clone.animate({opacity: 1},animateMs/2, function(){
		    	$clone.animate({opacity: 0},animateMs/2, callback );
		    });
		    };
		    
		    blink(blink);
			    
		};
				
	});
	
	
})(jQuery)