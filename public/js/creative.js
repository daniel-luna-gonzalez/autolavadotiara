define(['jquery',
//    'pagepiling'
    'easing',
    'scrolling'
],
        function($,
                easing,
                scrolling
//pagePilling,
                ) {
            "use strict"; // Start of use strict
            $(document).ready(function() {
                $(document).click(function(event) {
                    var clickover = $(event.target);
                    var $navbar = $(".navbar-collapse");
                    var _opened = $navbar.hasClass("in");
                    if (_opened === true && !clickover.hasClass("navbar-toggle")) {
                        $navbar.collapse('hide');
                    }
                });
                $('.arrow').click(function() {
                    var $this = $(this),
                            $next = $this.closest("section").next();
                    $('html, body').stop().animate({
                        scrollTop: $next.offset().top
                    }, 1500, 'easeInOutExpo');
                });


            });
        }
);
