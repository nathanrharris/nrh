(function ($, Drupal, drupalSettings, once) {
  Drupal.behaviors.frontVideo = {
    attach: function (context, settings) {
      $('body').once('homeVideoOnce').mousedown(function() {
        if ($('video source').attr('src') != '/themes/custom/nrh/videos/nathan-working.mp4') {
          $('video source').attr('src', '/themes/custom/nrh/videos/nathan-working.mp4');
          $('video')[0].load();
        }
      });
      
      $(window).once('homeVideoScrollOnce').scroll(function() {
        if ($('video source').attr('src') != '/themes/custom/nrh/videos/nathan-working.mp4') {
          $('video source').attr('src', '/themes/custom/nrh/videos/nathan-working.mp4');
          $('video')[0].load();
        }
      });
    }
  },

  Drupal.behaviors.frontCode = {
    attach: function (context, settings) {
      $(window).scroll(function() { scroll_handler(); });

      if ($('body').hasClass('path-frontpage')) {
        setInterval(function () {
          var pos1 = parseInt($('.path-frontpage .pane-code-1').css('background-position-y').replace('px',''));
          var pos2 = parseInt($('.path-frontpage .pane-code-2').css('background-position-y').replace('px',''));
          var pos3 = parseInt($('.path-frontpage .pane-code-3').css('background-position-y').replace('px',''));

          $('.path-frontpage .pane-code-1').css('background-position-y', (pos1 + 1) + 'px');
          $('.path-frontpage .pane-code-2').css('background-position-y', (pos2 + 2) + 'px');
          $('.path-frontpage .pane-code-3').css('background-position-y', (pos3 + 3) + 'px');
        }, 50);
      }

      function scroll_handler() {
        var pos = $(window).scrollTop();
        var h = $(window).height();
        var w = $(window).width();

        if (pos > (h * .9)) {
          $('#icons').addClass('icon-adjust');
        } else {
          $('#icons').removeClass('icon-adjust');
        }

        $('.path-frontpage .pane-code-1').css('background-position-y', (pos/-2));
        $('.path-frontpage .pane-code-2').css('background-position-y', (pos/-4));
        $('.path-frontpage .pane-code-3').css('background-position-y', (pos/-10));

        $('#interior-header-bg-1').css('background-position-y', (1425 + pos/-2));
        $('#interior-header-bg-2').css('background-position-y', (1425 + pos/-4));
        $('#interior-header-bg-3').css('background-position-y', (1425 + pos/-10));
      }
    }
  }

} (jQuery, Drupal, drupalSettings, once));
