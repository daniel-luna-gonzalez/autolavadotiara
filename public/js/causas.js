
define(["jquery", "jquery.bxslider"], function($, bx) {
    var Causas = function() {
        this.init = function() {
            $(document).ready(function() {
                var causas = $('#causas-slider').bxSlider({
                    slideSelector: 'div.slide',
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

    return new Causas();
});