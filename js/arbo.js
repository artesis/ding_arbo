(function ($) {
  $(document).ready(function() {
    $('#slider').tinycarousel({ display: 3 });
  });

  Drupal.ajax.prototype.commands['ding_arbo_widget_init'] = function (ajax, response, status) {
    Drupal.arbo.theme_path = '/' + response.arbo_path;
    Drupal.arbo.movie_name = response.movie_name;
    Drupal.arbo.application_name = response.app_name;
    Drupal.arbo.config_file = Drupal.arbo.theme_path + '/web.xml';
    Drupal.arbo.stream_path = response.stream_path;
 
    Drupal.arbo.init();
  };

  Drupal.arbo = {
    theme_path : '',
    movie_name : '',
    application_name : '',
    config_file : '',
    stream_path : '',
    stepIndex : 0,
    stepArray : '',

    init: function() {
     $('#arbo-widget div#scrubber a#player').attr('href', Drupal.arbo.stream_path + Drupal.arbo.movie_name + '.flv');

     // Bind ratings on mouse over and out
      $('#step3 div.user-rate div.rating').mouseover(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("#step3 div.user-rate div.rating:lt(" + ($(this).index() + 1) + ")").removeClass('star-off').removeClass('star-on').addClass('star-black');
          $("#step3 div.user-rate div.rating:gt(" + $(this).index() + ")").removeClass('star-black').removeClass('star-on').addClass('star-off');
        }
      });
      
      // Restore the stars after mouseout
      $('#step3 div.user-rate').mouseleave(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("#step3 div.user-rate div.rating:lt(" + Drupal.voxb_item.initial_rating + ")").removeClass('star-off').removeClass('star-black').addClass('star-on');
          $("#step3 div.user-rate div.rating:gt(" + (Drupal.voxb_item.initial_rating - 1) + ")").removeClass('star-on').removeClass('star-black').addClass('star-off');
        }
      });

      // Show the rating ajax animation
      $('#step3 div.user-rate div.rating').click(function() {
        if (!Drupal.voxb_item.rating_set) {
          $('#step3 .ajax-anim').show();
          Drupal.voxb_item.rating_set = true;
        }
      });

     // Initializes the upper tabs
     Drupal.arbo.stepIndex = 0;
     Drupal.arbo.stepArray = new Array($('#step1'), $('#step2'), $('#step3'), $('#step4'), $('#step5'));
     Drupal.arbo.showStep(Drupal.arbo.stepArray[Drupal.arbo.stepIndex]);
     Drupal.arbo.drawProgress();

     // Handle the next tab click
     $('#go-next').click(function() {
         // Increase stepIndex
        Drupal.arbo.stepIndex++;

        if (Drupal.arbo.stepIndex <= Drupal.arbo.stepArray.length-1) {
          Drupal.arbo.showStep(Drupal.arbo.stepArray[Drupal.arbo.stepIndex]);
        }
        return false;
      });

      // Handle the prev tab click
      $('#go-prev').click(function() {
         // Decrease stepIndex
        Drupal.arbo.stepIndex--;
        if (Drupal.arbo.stepIndex >= 0) {
          Drupal.arbo.showStep(Drupal.arbo.stepArray[Drupal.arbo.stepIndex]);
        }
        return false;
      });

      // Start recording
      $('a#record').click(function(){
        Drupal.arbo.startRecord();
        $('#go-next').hide();
        return false;
      });

      // Stop recording
      $('a#stop').click(function() {
        callToActionscript('stop');
        return false;
      });
    },

    startRecord: function() {
      var seconds = 3;
      var timer = '';
      $('#arbo-widget div.record-controls a#record').hide();
      $('#arbo-widget div.record-controls').prepend('<p style="font-size: 26px; text-align:center;">'+seconds+'</p>');

      timer = setInterval(function(){
        seconds--;
        if (seconds == 0) {
         $('#arbo-widget div.record-controls p').remove();
         clearInterval(timer);
          callToActionscript('record');
        }
        $('#arbo-widget div.record-controls p').html(seconds);
      }, 1000);
    },

    // Init tab headers functionality
    drawProgress : function () {
      progressContainer = $('#progress').empty();

      // Draw each step
      $(Drupal.arbo.stepArray).each(function(i) {
        progressClone = $('#tools #progress-clone').clone().removeAttr('id');

        if(i < Drupal.arbo.stepIndex) {
          progressClone.addClass('previous');
          // Add click handler
          progressClone.click(function() {
            Drupal.arbo.stepIndex = i;
            Drupal.arbo.showStep(Drupal.arbo.stepArray[i]);
          });
        }
        else if(i > Drupal.arbo.stepIndex) {
          progressClone.addClass('future');
        }
        if(i == Drupal.arbo.stepIndex) {
          progressClone.addClass('current');
        }

        progressContainer.append(progressClone.html($(this).children('h1').html()));
      });
    },

    // Init prev/next buttons
    showStep : function(stepHandle) {
      $('.step-container').hide();
      $(stepHandle).show();

      // Control showing/hiding of back/next buttons
      if (Drupal.arbo.stepIndex == 0) {
        $('#go-prev').hide();
        $('#go-next').hide();
      }
      else if (Drupal.arbo.stepIndex == Drupal.arbo.stepArray.length-1) {
        $('#go-next').hide();
      }
      else {
        // If not first or last step, then make sure that both buttons are shown
        $('#go-prev').show();
        $('#go-next').show();
      }

      // Retireve the reviewer email from prev step
      if (Drupal.arbo.stepIndex == 4) {
        var email = $('#arbo-widget input[name=email]').val();
        $('#arbo-widget input[name=review_email]').val(email);
      }

     Drupal.arbo.drawProgress();
    }
  };
})(jQuery);

// Flash calls this method on init
  function sendToJSOnLoad()
  {
    getFlashMovie(Drupal.arbo.application_name).sendToASXmlName(Drupal.arbo.config_file);
  }

  // Getter of movie
  function getFlashMovie(movie)
  {
    var isIE = navigator.appName.indexOf('Microsoft') != -1;

    return (isIE) ? window[movie] : document[movie];
  }

  // Send to flash specific commands
  function callToActionscript(str)
  {
    if (str == 'record') {
      jQuery('#stop').show();
      jQuery('#record').hide();
    }
    else if (str == 'stop') {
      jQuery('#stop').hide();
      jQuery('#record').show();
      jQuery('#go-next').show();
      flowplayer('player', Drupal.arbo.theme_path+'/swf/flowplayer-3.2.7.swf');
    }

    getFlashMovie(Drupal.arbo.application_name).sendToActionscript(str);
  }

  // Flash sends the recorded length here
  function sendToJSTime(value)
  {
    jQuery('#time').html(value);
  }

  /**
   * This method is called by Flash
   * Probbaly we will get errors if it will be removed
   */
  function sendToJSVideoName(value) { }
