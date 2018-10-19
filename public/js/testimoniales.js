
define(["jquery", "jquery.bxslider"], function($, bx) {
    var Testimoniales = function() {
        this.init = function() {
            $(document).ready(function() {

                var causas = $('#testimoniales-slider').bxSlider({
                    slideSelector: 'div.slide-t',
                    default: true,
//                    auto: true,
                    controls: false,
                    adaptiveHeight: true

                });

                $(window).resize(function() {
                    // causas.reloadSlider();
                });
            });


        };
    };

    return new Testimoniales();
});