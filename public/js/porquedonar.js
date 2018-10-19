/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define(["jquery", "jquery.bxslider"], function($, bx) {
    var PD = function() {
        this.init = function() {
            $(document).ready(function() {
                var porquedonar = $('#porquedonar-slider').bxSlider({
                    slideSelector: 'div.slide',
                    default: true,
//                    auto: true,
                    controls: false,
                    adaptiveHeight: true

                });

                $(window).resize(function() {
                    porquedonar.reloadSlider();
                });

            });
        };
    };

    return new PD();
});