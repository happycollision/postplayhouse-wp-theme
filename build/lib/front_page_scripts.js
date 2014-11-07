(function($) {
    $(window).load(function() {
        var slideshow = function() {
            $hero = $("#hero-slideshow");
            function next() {
                var $selected = $hero.find(".active").removeClass("active");
                var divs = $selected.parent().children();
                divs.eq((divs.index($selected) + 1) % divs.length).addClass("active");
            }
            window.sInterval = setInterval(next, 8e3);
        };
        slideshow();
    });
})(jQuery);