(function ($) {
  Drupal.ajax.prototype.commands['ding_arbo_widget_init'] = function (ajax, response, status) {
    Drupal.arbo.theme_path = '/' + response.arbo_path;
    Drupal.arbo.movie_name = response.movie_name;
    Drupal.arbo.application_name = response.app_name;
    Drupal.arbo.config_file = Drupal.arbo.theme_path + '/web.xml';
    Drupal.arbo.stream_path = response.stream_path;
 
    Drupal.arbo.init();
  };
  
  // overwrite voxb_rating_callback command to work right with ARBO popup
  Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
    // update rating count
    $('.ratingCountSpan').html('(' + response.rating_count + ')');

    // show thank message
    $('.ratingsContainer .ajax_message').show();
    $('.arbo-ratingsContainer .ajax_message').show();

    // unbind mouse over/out on start
    $('div.userRate').hide();
    $('div.arbo-userRate').hide();

    // update rating
    $("div.ratingStars div.rating:lt(" + response.rating + ")").removeClass('star-off').addClass('star-on');
    $("div.ratingStars div.rating:gt(" + (response.rating - 1) + ")").removeClass('star-on').addClass('star-off');

    $("div.arbo-ratingStars div.rating:lt(" + response.rating + ")").removeClass('star-off').addClass('star-on');
    $("div.arbo-ratingStars div.rating:gt(" + (response.rating - 1) + ")").removeClass('star-on').addClass('star-off');
  }

  Drupal.arbo = {
    theme_path : '',
    movie_name : '',
    application_name : '',
    config_file : '',
    stream_path : '',
    stepIndex : 0,
    stepArray : '',

    init: function() {
     $('#arbo_widget div#scrubber a#player').attr('href', Drupal.arbo.stream_path + Drupal.arbo.movie_name + '.flv');
     $('.stepContainer').hide();
     $('#step1').show();

     // Bind ratings on mouse over and out
     $('div.arbo-userRate div.rating').mouseover(function(){
      $("div.arbo-userRate div.rating:lt(" + ($(this).index() + 1) + ")").removeClass('star-off').addClass('star-on');
      $("div.arbo-userRate div.rating:gt(" + $(this).index() + ")").removeClass('star-on').addClass('star-off');
     });

     $('div.arbo-userRate div.rating').mouseleave(function() {
      $("div.arbo-userRate div.rating").removeClass('star-on').addClass('star-off');
     });

     // Initializes the upper tabs
     Drupal.arbo.stepArray = new Array($('#step1'), $('#step2'), $('#step3'), $('#step4'), $('#step5'));
     Drupal.arbo.stepIndex = 0;
     Drupal.arbo.showStep(Drupal.arbo.stepArray[0]);
     Drupal.arbo.drawProgress();

     // Handle the next tab click
     $('#goNext').click(function() {
         // Increase stepIndex
        Drupal.arbo.stepIndex++;

        if (Drupal.arbo.stepIndex <= Drupal.arbo.stepArray.length-1) {
         Drupal.arbo.showStep(Drupal.arbo.stepArray[Drupal.arbo.stepIndex]);
        }
        return false;
      });

      // Handle the prev tab click
      $('#goPrev').click(function() {
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
       $('#goNext').hide();
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
      $('#arbo_widget div.record_controls a#record').hide();
      $('#arbo_widget div.record_controls').prepend('<p style="font-size: 26px; text-align:center;">'+seconds+'</p>');

      timer = setInterval(function(){
        seconds--;
        if (seconds == 0) {
         $('#arbo_widget div.record_controls p').remove();
         clearInterval(timer);
          callToActionscript('record');
        }
        $('#arbo_widget div.record_controls p').html(seconds);
      }, 1000);
    },

    // Init tab headers functionality
    drawProgress : function () {
      progressContainer = $('#progress').empty();

      // Draw each step
      $(Drupal.arbo.stepArray).each(function(i) {
        progressClone = $('#tools #progressClone').clone().removeAttr('id');

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
      $('.stepContainer').hide();
      $(stepHandle).show();

      // Control showing/hiding of back/next buttons
      if (Drupal.arbo.stepIndex == 0) {
        $('#goPrev').hide();
        $('#goNext').hide();
      }
      else if (Drupal.arbo.stepIndex == Drupal.arbo.stepArray.length-1) {
        $('#goNext').hide();
      }
      else {
        // If not first or last step, then make sure that both buttons are shown
        $('#goPrev').show();
        $('#goNext').show();
      }

      // Retireve the reviewer email from prev step
      if (Drupal.arbo.stepIndex == 4) {
        var email = $('#arbo_widget input[name=email]').val();
        $('#arbo_widget input[name=review_email]').val(email);
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
      jQuery('#goNext').show();
      flowplayer('player', Drupal.arbo.theme_path+'/swf/flowplayer-3.2.7.swf');
    }

    getFlashMovie(Drupal.arbo.application_name).sendToActionscript(str);
  }

  // Flash sends the recorded length here
  function sendToJSTime(value)
  {
    jQuery('#time').html(value);
  }

  /*
   * This method is called by Flash
   * Probbaly we will get errors if it will be removed
   */
  function sendToJSVideoName(value) { }
