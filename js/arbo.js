var ArboWidget = {

  videoName : '',
  applicationName : 'ARBO',
  streamServer : '',
  configFileName : ArboConfig.themePath+'/web.xml',
  movie_exists : false,
  stepIndex : 0,
  stepArray : '',


  init: function(){
    // Launch the widget on link click
    jQuery('h1#arbo_review a').click(function() {
      jQuery('#arbo_widget').css('height', '600px');
      jQuery('#widget_block').show();
      jQuery('#widget_response').html('');
      jQuery('#widget_block input#accept').removeAttr('checked');
      jQuery('#widget_block button#submit').removeAttr('disabled');
      ArboWidget.movie_exists = false;
      ArboWidget.stepIndex = 0;
      ArboWidget.stepArray = new Array(jQuery('#step1'), jQuery('#step2'), jQuery('#step3'), jQuery('#step4'), jQuery('#step5'));
      ArboWidget.showStep(ArboWidget.stepArray[ArboWidget.stepIndex]);

      jQuery('#arbo_widget').bPopup({
        modalClose:false,
        zIndex:9700,
        follow:false
      });

      return false;
    });

    jQuery('#arbo_widget p.close a').click(function() {
      jQuery('#arbo_widget').bPopup().close();
      ArboWidget.setMovieName();

      return false;
    });

    jQuery('div.errorPopup p.close a').click(function(){
      jQuery('div.errorPopup').bPopup().close();

      return false;
    });

    jQuery('#yt_player p.close a').click(function(){
      jQuery('#yt_player').bPopup().close();

      return false;
    });

    jQuery('#mycarousel a.review_link').click(function() {
      var yt_link = jQuery(this).attr('href');
      var yt_id = yt_link.split('=');

      jQuery('div#yt_player').children().not('p.close').remove();
      jQuery('div#yt_player').append('<iframe title="YouTube video player" width="480" height="390" src="http://www.youtube.com/embed/'+yt_id[1]+'" frameborder="0" allowfullscreen></iframe>');
      jQuery('div#yt_player').bPopup({zIndex: 9800});
      return false;
    });

    // Submit a video review
    jQuery('#arbo_widget button#submit').click(function() {
      if (jQuery('input#accept:checked').length > 0) {
        jQuery('#widget_block').hide();
        jQuery('#arbo_widget').animate({'height':'180px'}, 500);
        jQuery('#arbo_widget p.close').hide();
        jQuery('#widget_response').html('<div class="loading"><img src="'+ArboConfig.themePath+'/img/loading.gif" width="200" height="100" alt="" /></div><p>Your video is beeing processed. <br />Please wait...</p>');
        jQuery.ajax({
          type: 'POST',
          url: '/arbo/call',
          data: {
            'movie_name': jQuery('#arbo_widget p.movie_name').html()+'.flv',
            'movie_title': jQuery('#arbo_widget p.title').html(),
            'movie_description': jQuery('#arbo_widget p.description').html(),
            'movie_tags': jQuery('#arbo_widget p.tags').html(),
            'faust_number': jQuery('#arbo_widget p.ac_identifier').html(),
            'user_email': jQuery('#arbo_widget input[name=email]').val(),
            'object_id': jQuery('#arbo_widget p.object_id').html()
          },
          dataType: 'text',
          success: function(msg) {
            var s = '';
            if (msg == '0x01') {
              s = 'Thank you for your review! You may close this overlay.';
              img = 'accept';
            }
            else {
              s = 'There was a problem uploading your review. Try again soon.';
              img = 'repeat-128';
            }

            jQuery('#arbo_widget p.close').show();
            jQuery('#widget_response').html('<div class="request_result"><div class="loading" style="width: 128px;"><img src="'+ArboConfig.themePath+'/img/'+img+'.png" width="128" height="128" alt="" /></div><p>'+s+'</p></div>');
          }
        });
        jQuery(this).attr("disabled", "disabled");
      }
      else {
       VoxbItem.showPopup('Terms!');
      }
    });

    jQuery('#goNext').click(function() {
      // Increase stepIndex
      ArboWidget.stepIndex++;

      if (ArboWidget.stepIndex <= ArboWidget.stepArray.length-1)
        ArboWidget.showStep(ArboWidget.stepArray[ArboWidget.stepIndex]);

      return false;
    });

    jQuery('#goPrev').click(function() {
      // Decrease stepIndex
      ArboWidget.stepIndex--;

      if (ArboWidget.stepIndex >= 0)
        ArboWidget.showStep(ArboWidget.stepArray[ArboWidget.stepIndex]);

      return false;
    });
  },

  showStep : function(stepHandle) {
    jQuery('.stepContainer').hide();
    jQuery(stepHandle).show();

    // Control showing/hiding of back/next buttons
    if (ArboWidget.stepIndex == 0) {
      jQuery('#goPrev').hide();
      jQuery('#goNext').hide();
    }
    else if (ArboWidget.stepIndex == ArboWidget.stepArray.length-1) {
      jQuery('#goNext').hide();
    }
    else {
      // If not first or last step, then make sure that both buttons are shown
      jQuery('#goPrev').show();
      jQuery('#goNext').show();
    }

    ArboWidget.drawProgress();
  },

  drawProgress : function() {
    progressContainer = jQuery('#progress').empty();

    // Draw each step
    jQuery(ArboWidget.stepArray).each(function(i) {
      progressClone = jQuery('#tools #progressClone').clone().removeAttr('id');

      if (i < ArboWidget.stepIndex) {
        progressClone.addClass('previous');
        // Add click handler
        progressClone.click(function() {
          ArboWidget.stepIndex = i;
          ArboWidget.showStep(ArboWidget.stepArray[i]);
        });
      } else if (i > ArboWidget.stepIndex) {
        progressClone.addClass('future');
      }
      if (i == ArboWidget.stepIndex) {
        progressClone.addClass('current');
      }

      progressContainer.append(progressClone.html(jQuery(this).children('h1').html()));
    });
  },

  setMovieName : function() {
    var l = 16;
    var chars = new Array(
      'a','b','c','d','e','f',
      'g','h','i','j','k','l',
      'm','n','o','p','q','r',
      's','t','u','v','w','x',
      'y','z','0','1','2','3',
      '4','5','6','7','8','9'
    );
    var hash = '';

    for (i = 0; i < l; i++) {
      hash += chars[Math.floor(Math.random() * chars.length)];
    }

    jQuery('#arbo_widget p.movie_name').html(hash);
    jQuery('#arbo_widget div.flash param[name=FlashVars]').attr('value', 'streamFileName='+hash);
    jQuery('#arbo_widget div.flash embed').attr('FlashVars', 'streamFileName='+hash);
    // Initialize the player
    jQuery('#arbo_widget div#scrubber a#player').attr('href', ArboConfig.flvPath+hash+'.flv');
    flowplayer('player', ArboConfig.themePath+'/swf/flowplayer-3.2.7.swf');
  },

  startRecord:function() {
    var seconds = 3;
    var timer = '';
    jQuery('#arbo_widget div.record_controls a#record').hide();
    jQuery('#arbo_widget div.record_controls').prepend('<p style="font-size: 26px; text-align:center;">'+seconds+'</p>');

    timer = setInterval(function(){
      seconds--;

      if (seconds == 0) {
        clearInterval(timer);
        callToActionscript('record');
        jQuery('#arbo_widget div.record_controls p').remove();
      }

      jQuery('#arbo_widget div.record_controls p').html(seconds);
    }, 1000);
  }
}

jQuery(document).ready(function() {
  ArboWidget.init();
  ArboWidget.setMovieName();
  ArboWidget.showStep(ArboWidget.stepArray[ArboWidget.stepIndex]);
});

function sendToJSOnLoad()
{
  getFlashMovie(ArboWidget.applicationName).sendToASXmlName(ArboWidget.configFileName);
}

function getFlashMovie(movieName)
{
  var isIE = navigator.appName.indexOf('Microsoft') != -1;
    
  return (isIE) ? window[movieName] : document[movieName];
}

function callToActionscript(str)
{
  if (str == 'record') {
    jQuery('#stop').show();
    jQuery('#record').hide();
    jQuery('#goNext').hide();
    ArboWidget.movie_exists = false;
  }
  else if (str == 'stop') {
    jQuery('#stop').hide();
    jQuery('#record').show();
    ArboWidget.movie_exists = true;
    jQuery('#goNext').show();
  }

  getFlashMovie(ArboWidget.applicationName).sendToActionscript(str);
}

function sendToJSTime(value)
{
  jQuery('#time').html(value);
}

    
function sendToJSVideoName(value)
{
  var matches = value.match(/^(.+\/)([^\/]+)  jQuery/);
  ArboWidget.videoName = matches ? matches[2] : '';
  ArboWidget.streamServer = matches ? matches[1] : '';
}
