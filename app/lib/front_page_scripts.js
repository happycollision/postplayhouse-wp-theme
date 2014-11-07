(function($){
	$(window).load(function(){
/*		var hcSlide = function(){
			var $hero = $('#hero-slideshow');
			var $currentSlide = $hero.find('a');
			var allSlidesData = $hero.find('#hero-data').data('slides');
			// remove teh $currentSlides duplicated data
			allSlidesData.shift();

			if(allSlidesData.length < 1) return;

			var $template = $currentSlide;
			var slides = [];
			for (var i = 0; i < allSlidesData.length; i++){
				$template.attr('href', allSlidesData[i].link);
				$template.find('img').attr('src', allSlidesData[i].image);
				$template.find('.title').text(allSlidesData[i].title);
				$template.find('.caption').text(allSlidesData[i].caption);
				slides.push($template);
			}


			function next(){
				var $next = slides.shift();

				$next.hide().appendTo($hero);
				$currentSlide.hide().detach();
				$next.show();
				slides.push($currentSlide);

				$currentSlide = $next;
			}

			setInterval(next,6000);

		}

		hcSlide();
*/

	var slideshow = function(){
		$hero = $('#hero-slideshow');
		function next(){
			var $selected = $hero.find(".active").removeClass("active");
	    var divs = $selected.parent().children();
	    divs.eq((divs.index($selected) + 1) % divs.length).addClass("active");
		}

		window.sInterval = setInterval(next, 8000);

		// //Pause on hover. Resume 2 secs later on hover out.
		// $hero.hover(function(){
		// 	clearInterval(window.sInterval)
		// }, function(){ 
		// 	window.sInterval = setInterval(next, 6000);
		// });
	}
	slideshow();

	});
})(jQuery)