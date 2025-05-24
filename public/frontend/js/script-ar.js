(function ($) {
    "use strict"; // Start of use strict
    //// MENU REPONSIIVE
    $('.promo-code .remove').click(function () {
        $('.promo-code').addClass("hide");
    });
    $('.user-side-menu h4').on('click', function (e) {
        $(".user-side-menu ul").slideToggle();
    });
    // Owl carousel
    $(".sliderhome").owlCarousel({
        navigation: true,
        dots: true,
        pagination: false,
        loop: true,
        animateOut: 'fadeOut',
        autoplay: 3000,
        autoplayTimeout: 3000,
        smartSpeed: 500,
        paginationSpeed: 3000,
        nav: true,
        items: 1,
        rtl: true
    });
    $(".home-products").owlCarousel({
        navigation: true,
        pagination: true,
        nav: true,
        dots: true,
        loop: true,
        animateOut: 'fadeOut',
        autoplay: 3000,
        autoplayTimeout: 3000,
        smartSpeed: 500,
        paginationSpeed: 3000,
        margin: 20,
        items: 4,
        rtl: true,
        navText: ['<span class="ti-angle-right"></span>', '<span class="ti-angle-left"></span>'],
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
    $(".est-categories-items").owlCarousel({
        navigation: true,
        pagination: true,
        nav: true,
        dots: true,
        loop: true,
//       animateOut: 'fadeOut',
        autoplay: 3000,
        autoplayTimeout: 3000,
        smartSpeed: 500,
        paginationSpeed: 3000,
        margin: 10,
        items: 7, // editable  = 5
        rtl: true,
        navText: ['<span class="ti-angle-right"></span>', '<span class="ti-angle-left"></span>'],
        responsive: {
            0: {
                items: 2
            },
            480: {
                items: 2
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            },
            1200: {
                items: 7 // editable  = 5
            }
        }
    });

    function init_carousel() {
        $('.owl-product').owlCarousel({
            items: 1,
            thumbs: true,
            thumbsPrerendered: true,
            rtl: true
        });
        $(".owl-carousel").each(function (index, el) {
            var config = $(this).data();
//            config.navText = ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'];
            var animateOut = $(this).data('animateout');
            var animateIn = $(this).data('animatein');
            var slidespeed = $(this).data('slidespeed');
            if (typeof animateOut != 'undefined') {
                config.animateOut = animateOut;
            }
            if (typeof animateIn != 'undefined') {
                config.animateIn = animateIn;
            }
            if (typeof (slidespeed) != 'undefined') {
                config.smartSpeed = slidespeed;
            }
            var owl = $(this);
            owl.on('initialized.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                }, 100);
            })
            owl.on('refreshed.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                }, 100);
            })
            owl.on('change.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                }, 100);
                owl.addClass('owl-changed');
                setTimeout(function () {
                    owl.removeClass('owl-changed');
                }, config.smartSpeed)
            })
            owl.on('drag.owl.carousel', function (event) {
                owl.addClass('owl-changed');
                setTimeout(function () {
                    owl.removeClass('owl-changed');
                }, config.smartSpeed)
            })
            owl.owlCarousel(config);
        });
    }

    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function () {
//Wow animate
        new WOW().init();
        // OWL CAROUSEL
        init_carousel();

        $(document).on('click', '.quantity .plus, .quantity .minus', function (e) {
// Get values
            var $qty = $(this).closest('.quantity').find('.qty'),
                currentVal = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')),
                step = $qty.attr('step');
            // Format values
            if (!currentVal || currentVal === '' || currentVal === 'NaN')
                currentVal = 0;
            if (max === '' || max === 'NaN')
                max = '';
            if (min === '' || min === 'NaN')
                min = 0;
            if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN')
                step = 1;
            // Change the value
            if ($(this).is('.plus')) {
                if (max && (max == currentVal || currentVal > max)) {
                    $qty.val(max);
                } else {
                    $qty.val(currentVal + parseFloat(step));
                }
            } else {
                if (min && (min == currentVal || currentVal < min)) {
                    $qty.val(min);
                } else if (currentVal > 0) {
                    $qty.val(currentVal - parseFloat(step));
                }
            }
// Trigger change event
            $qty.trigger('change');
            e.preventDefault();
        });
        // menu on mobile
        $(".header-nav .toggle-submenu").on('click', function () {
            $(this).parent().toggleClass('open-submenu');
            return false;
        });

        $("[data-action='toggle-nav']").on('click', function () {
            $(this).toggleClass('active');
            $(".header-nav").toggleClass("has-open");
            return false;
        });
        $(".header-menu .btn-close").on('click', function () {
            $('.header-nav').removeClass('has-open');
            return false;
        });
        //chosen-select
        if ($('.chosen-select').length > 0) {
            $('.chosen-select').chosen();
        }

        $('.collapseWill').on('click', function (e) {
            $(this).toggleClass("pressed"); //you can list several class names
            e.preventDefault();
        });
        $('.sp-wrap').smoothproducts();
    });
    $('.masterKeynet').on('click', function (e) {
        $(this).addClass("cut-radio-style");
        $('.masterCard').removeClass("cut-radio-style");
    });
    $('.masterCard').on('click', function (e) {
        $(this).addClass("cut-radio-style");
        $('.masterKeynet').removeClass("cut-radio-style");
    });
    if ($('#google-map').length > 0) {
        //set your google maps parameters
        var latitude = 51.5255069,
            longitude = -0.0836207,
            map_zoom = 14;

        //google map custom marker icon
        var marker_url = 'images/map-marker.png';

        //we define here the style of the map
        var style = [{
            "featureType": "landscape",
            "stylers": [{"saturation": -100}, {"lightness": 65}, {"visibility": "on"}]
        }, {
            "featureType": "poi",
            "stylers": [{"saturation": -100}, {"lightness": 51}, {"visibility": "simplified"}]
        }, {
            "featureType": "road.highway",
            "stylers": [{"saturation": -100}, {"visibility": "simplified"}]
        }, {
            "featureType": "road.arterial",
            "stylers": [{"saturation": -100}, {"lightness": 30}, {"visibility": "on"}]
        }, {
            "featureType": "road.local",
            "stylers": [{"saturation": -100}, {"lightness": 40}, {"visibility": "on"}]
        }, {
            "featureType": "transit",
            "stylers": [{"saturation": -100}, {"visibility": "simplified"}]
        }, {"featureType": "administrative.province", "stylers": [{"visibility": "off"}]}, {
            "featureType": "water",
            "elementType": "labels",
            "stylers": [{"visibility": "on"}, {"lightness": -25}, {"saturation": -100}]
        }, {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{"hue": "#ffff00"}, {"lightness": -25}, {"saturation": -97}]
        }];

        //set google map options
        var map_options = {
            center: new google.maps.LatLng(latitude, longitude),
            zoom: map_zoom,
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            streetViewControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            styles: style,
        }
        //inizialize the map
        var map = new google.maps.Map(document.getElementById('google-map'), map_options);
        //add a custom marker to the map
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(latitude, longitude),
            map: map,
            visible: true,
            icon: marker_url,
        });
    }

})(jQuery); // End of use strict


//$(document).ready(function () {
//    $(document).on("scroll", onScroll);
//
//    //smoothscroll
//    $('.categories a[href^="#"]').on('click', function (e) {
//        e.preventDefault();
//        $(document).off("scroll");
//
//        $('.categories a').each(function () {
//            $(this).removeClass('active');
//        });
//        $(this).addClass('active');
//
//        var target = this.hash,
//                menu = target;
//        $target = $(target);
//        $('html, body').stop().animate({
//            'scrollTop': $target.offset().top + 2
//        }, 500, 'swing', function () {
//            window.location.hash = target;
//            $(document).on("scroll", onScroll);
//        });
//    });
//});

//function onScroll(event) {
//    var scrollPos = $(document).scrollTop();
//    $('.categories a').each(function () {
//
//        var currLink = $(this);
//        var refElement = $(currLink.attr("href"));
//        if (refElement.offset().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
//            $('.categories a').removeClass("active");
//            currLink.addClass("active");
//        } else {
//            currLink.removeClass("active");
//        }
//    });
//}
$('body').scrollspy({
    target: '.categories',
    offset: 80
});
$(window).scroll(function () {


    if (matchMedia('only screen and (min-width: 1200px)').matches) {
        if ($(window).scrollTop() >= 400) {
            $('.categories').addClass('stickysidebar');
            $('.mini-cart').addClass('stickysidebar');
        } else {

            $('.categories').removeClass('stickysidebar');
            $('.mini-cart').removeClass('stickysidebar');
        }
    }
    if (matchMedia('only screen and (max-width: 768px)').matches) {
        if ($(window).scrollTop() >= 300) {
            $('.categories').addClass('stickysidebar');
            $('.account-setting').addClass('padding-t');
        } else {

            $('.categories').removeClass('stickysidebar');
            $('.account-setting').removeClass('padding-t');
        }
    }


});
