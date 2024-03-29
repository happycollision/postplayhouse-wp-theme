(function($) {
    $.fn.lowFloat = function(options) {
        var opts = $.extend({}, $.fn.lowFloat.defaultOptions, options);
        this.each(function() {
            var container = $(this).parent().closest(opts.container);
            container.prepend(this);
            $(this).css({
                "float": opts.float,
                clear: opts.float
            });
            $("<div>").css({
                width: 0,
                "float": opts.float
            }).insertBefore(this);
        });
        var floaters = this;
        $(window).on("resize", function() {
            floaters.each(function() {
                var floater = $(this);
                var spacer = floater.prev();
                var container = floater.parent();
                var lastContent = container.find(opts.bottomContent);
                var floaterHeight = this.offsetHeight;
                var h = Math.max(0, container.height() - floaterHeight);
                spacer.height(h);
                while (h > 0 && floater.offset().top + floaterHeight > lastContent.offset().top + lastContent.height()) {
                    spacer.height(--h);
                }
                while (floater.offset().top + floaterHeight < lastContent.offset().top + lastContent.height()) {
                    spacer.height(++h);
                }
            });
        }).trigger("resize");
        return this;
    };
    $.fn.lowFloat.defaultOptions = {
        container: "*",
        bottomContent: "> :last-child",
        "float": "right"
    };
})(jQuery);