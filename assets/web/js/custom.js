jQuery(document).ready(function($) {


   //show popup modal once every 24hrs
  if ($.cookie('popup') == null) {
    setTimeout(function() {
      $('#popup_ad').modal();
    }, 10000);
    $.cookie('popup', '1');
  }


  //Contact Us
  $('#contact_us_form').submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      url: base_url + 'web/contact_us_ajax', 
      type: 'POST',
      data: form_data, 
      success: function(msg) {
        if (msg == 1) {
          $( '#status_msg' ).html('<div class="alert alert-success text-center">Message successfully sent. We\'ll get in touch with you shortly.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
          $('#contact_us_form')[0].reset(); //reset form fields
		      $('#captcha_code').val(''); //clear captcha field
        } else {
          $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
        }
      }
    });
  });



  // Header fixed and Back to top button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
      $('#header').addClass('header-fixed');
    } else {
      $('.back-to-top').fadeOut('slow');
      $('#header').removeClass('header-fixed');
    }
  });
  $('.back-to-top').click(function() {
    $('html, body').animate({
      scrollTop: 0
    }, 1500, 'easeInOutExpo');
    return false;
  });

  // Initiate the wowjs animation library
  new WOW().init();

  // Initiate superfish on nav menu
  $('.nav-menu').superfish({
    animation: {
      opacity: 'show'
    },
    speed: 400
  });

  // Mobile Navigation
  if ($('#nav-menu-container').length) {
    var $mobile_nav = $('#nav-menu-container').clone().prop({
      id: 'mobile-nav'
    });
    $mobile_nav.find('> ul').attr({
      'class': '',
      'id': ''
    });
    $('body').append($mobile_nav);
    $('body').prepend('<button type="button" id="mobile-nav-toggle"><i class="fa fa-bars"></i></button>');
    $('body').append('<div id="mobile-body-overly"></div>');
    $('#mobile-nav').find('.menu-has-children').prepend('<i class="fa fa-chevron-down"></i>');

    $(document).on('click', '.menu-has-children i', function(e) {
      $(this).next().toggleClass('menu-item-active');
      $(this).nextAll('ul').eq(0).slideToggle();
      $(this).toggleClass("fa-chevron-up fa-chevron-down");
    });

    $(document).on('click', '#mobile-nav-toggle', function(e) {
      $('body').toggleClass('mobile-nav-active');
      $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
      $('#mobile-body-overly').toggle();
    });

    $(document).click(function(e) {
      var container = $("#mobile-nav, #mobile-nav-toggle");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
          $('#mobile-body-overly').fadeOut();
        }
      }
    });
  } else if ($("#mobile-nav, #mobile-nav-toggle").length) {
    $("#mobile-nav, #mobile-nav-toggle").hide();
  }

  // Smoth scroll on page hash links
  $('.nav-menu a, #mobile-nav a, .scrollto').on('click', function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      if (target.length) {
        var top_space = 0;

        if ($('#header').length) {
          top_space = $('#header').outerHeight();

          if( ! $('#header').hasClass('header-fixed') ) {
            top_space = top_space - 20;
          }
        }

        $('html, body').animate({
          scrollTop: target.offset().top - top_space
        }, 1500, 'easeInOutExpo');

        if ($(this).parents('.nav-menu').length) {
          $('.nav-menu .menu-active').removeClass('menu-active');
          $(this).closest('li').addClass('menu-active');
        }

        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
          $('#mobile-body-overly').fadeOut();
        }
        return false;
      }
    }
  });

  // Gallery - uses the magnific popup jQuery plugin
  $('.gallery-popup').magnificPopup({
    type: 'image',
    removalDelay: 300,
    mainClass: 'mfp-fade',
    gallery: {
      enabled: true
    },
    zoom: {
      enabled: true,
      duration: 300,
      easing: 'ease-in-out',
      opener: function(openerElement) {
        return openerElement.is('img') ? openerElement : openerElement.find('img');
      }
    }
  });



   /* ==========================================================================
   Core Features Owl Carousel
   ========================================================================== */
  $("#features-slider").owlCarousel({
      pagination: false,
      navigation : false,
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      stopOnHover: true,
      items: 4,
      itemsDesktop: [1199, 4],
      itemsDesktopSmall: [979, 3],
      itemsTablet: [600, 2],
      itemsMobile: [479, 1]
  });


  /* ==========================================================================
   Testimonial Owl Carousel
   ========================================================================== */
  $(".testimonial-slider").owlCarousel({
      navigation: true,
      pagination: false,
      slideSpeed: 1000,
      stopOnHover: true,
      autoPlay: true,
      items: 1,
      itemsDesktopSmall: [1024, 1],
      itemsTablet: [600, 1],
      itemsMobile: [479, 1]
  });
  $('.testimonial-slider').find('.owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.testimonial-slider').find('.owl-next').html('<i class="fa fa-angle-right"></i>');


  /* ==========================================================================
   Screenshots Owl Carousel
   ========================================================================== */
  $("#screenshots-slider").owlCarousel({
      pagination: false,
      navigation : true,
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      stopOnHover: true,
      items: 3,
      itemsDesktop: [1199, 3],
      itemsDesktopSmall: [979, 3],
      itemsTablet: [600, 1],
      itemsMobile: [479, 1]
  });
  $('#screenshots-slider').find('.owl-prev').html('<span>&laquo;</span>');
  $('#screenshots-slider').find('.owl-next').html('<span>&raquo;</span>');
  

});
