<?php

/**
 * @file
 *
 * ARBO widget recorder implementation
 *
 * Displays a multi-step widget to place own video review to a specific item,
 * fetched from ting.
 *
 * All requests are sent and processed at red5 installation.
 */

// Include files
require_once(ARBO_PATH . '/lib/VoxbVideoReviews.class.php');
require_once(VOXB_PATH . '/lib/VoxbItem.class.php');

$ac_identifier = explode('|', $object->record['ac:identifier'][''][0]);
$ac_identifier = $ac_identifier[0];

$obj = new VoxbItem();
$obj->addReviewHandler('videoreview', new VoxbVideoReviews());
$obj->fetchByFaust($ac_identifier);


$inline_js = "var ArboConfig = {
  themePath : '/".ARBO_PATH."',
  flvPath : '".variable_get('arbo_flv_server_addr', FLV_ADDR)."'
}";

drupal_add_js($inline_js, 'inline');

if ($user->uid != 0) {
  drupal_add_js(ARBO_PATH.'/js/arbo.js', 'file');
  drupal_add_js(ARBO_PATH.'/js/flowplayer-3.2.6.min.js', 'file');
}

drupal_add_js(ARBO_PATH.'/js/jquery.jcarousel.lite.js', 'file');

?>

<style type="text/css">@import url("/<?php echo ARBO_PATH; ?>/css/arbo-widget.css");</style>
<?php if ($user->uid != 0) : ?>
<style type="text/css">@import url("/<?php echo ARBO_PATH; ?>/css/arbo-lightbox.css");</style>

<div id="arbo_widget_response"><p class="close"><a href="javascript: void();"><img src="<?php echo ARBO_PATH; ?>/img/cancel-on.png" alt="" /></a></p></div>
<div id="arbo_widget">
  <p class="close"><a href="javascript: void();"><img src="/<?php echo ARBO_PATH; ?>/img/cancel-on.png" alt="" /></a></p>
  <div id="widget_block">
    <!-- This displays the current progress in the wizard -->
    <div id="progress"></div>
    <div class="clear"></div>
    <br />

    <!-- Contains all the steps -->
    <!-- Step 1 - Record -->
    <div id="step1" class="stepContainer">
      <h1 class="step_title">Optag</h1>
      <p><?php print t('Press the record button to record your review'); ?></p>
      <div class="record_controls">
        <a href="javascript:void(0);" onClick="javascript:ArboWidget.startRecord();" id="record"><img src="/<?php echo ARBO_PATH; ?>/img/record-32.png" /></a>
        <a href="javascript:void(0);" onClick="javascript:callToActionscript('stop');" style="display: none;" id="stop"><img src="/<?php echo ARBO_PATH; ?>/img/stop-32.png" /></a>
      </div>
      <div class="time">
        <p id="time">00:00:00</p>
      </div>
      <div class="flash">
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="ARBO" width="500" height="385" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
          <param name="movie" value="/<?php echo ARBO_PATH; ?>/swf/ARBO.swf" />
          <param name="quality" value="best" />
          <param name="bgcolor" value="#869ca7" />
          <param name="allowNetworking" value="true" />
          <param name="allowScriptAccess" value="always" />
          <param name="FlashVars" value="streamFileName=" />
          <embed src="/<?php echo ARBO_PATH; ?>/swf/ARBO.swf" quality="best" bgcolor="#869ca7"
            width="500" height="385" name="ARBO" align="middle"
            play="true" loop="false" quality="high" allowNetworking="true"
            allowScriptAccess="always" type="application/x-shockwave-flash"
            pluginspage="http://www.adobe.com/go/getflashplayer"
            FlashVars="streamFileName=">
          </embed>
        </object>
      </div>
      <div class="clear"></div>
    </div>
    <!-- Step 2 - View recorded video -->
    <div id="step2" class="stepContainer">
      <h1 class="step_title"><?php print t('Approve'); ?></h1>
      <p><?php print t('Look your video through and press the green button to proceed'); ?></p> 
      <div id="scrubber">
        <a  
         href=""
         style="display:block;width:500px;height:350px"  
         id="player"> 
        </a>
      </div>
      <p><?php print t('Tip: you can retry by pressing the back-button'); ?></p>
      <div class="clear"></div>
    </div>
    <!-- Step 3 - Tag/rate functionality -->
    <div id="step3" class="stepContainer">
      <h1 class="step_title">Tag/Rate</h1>
      <?php

      // Tags
      echo '<h2>Tag this item</h2><div class="recordTagHighlight">';
      foreach ($obj->getTags() as $v) {
       echo '<span class="tag"><a href="/search/ting/'.htmlspecialchars($v->getName()).'">'.htmlspecialchars($v->getName()).'</a></span>&nbsp;';
      }
      echo '</div>';

      // Add tag form
      echo '<div class="addTagContainer"><input type="text" maxlength="32" size="20" name="tag_name" />&nbsp;&nbsp;<input type="button" name="add_tag_btn" value="'.t('Add tag').'" /><img  class="ajax_anim" src="/'. VOXB_PATH . '/img/ajax-loader.gif" width="16" height="16" alt="" /><p class="ajax_message">'.t('Thank you for contributing.').'</p></div>';

      // Rating
      $rating = $obj->getRating();
      echo '<hr />';
      echo '<h2>Rate this item</h2><div class="addRatingContainer"><span class="ratingStars userRate">';
      for ($i = 1; $i <= 5; $i++) {
        echo '<img "src="/'. VOXB_PATH . '/img/star-off.png" alt="">';
      }
      if ($obj->getRatingCount() > 0) {
        echo '<span class="ratingCountSpan"> (<span class="ratingVotesNumber">'.$obj->getRatingCount().'</span>) </span>';
      }
      echo '</span><img class="ajax_anim" src="/'. VOXB_PATH . '/img/ajax-loader.gif" width="16" height="16" alt="" /><p class="ajax_message">'.t('Thank you for contributing.').'</p></div>';
      ?>
      <div class="clear"></div>
    </div>
    <!-- Step 4 - Mail confirmation -->
    <div id="step4" class="stepContainer">
      <h1 class="step_title">Confirm</h1>
      <p><?php print t('We would like to confirm you when your video review is processed and available, please type your email address below'); ?></p>
      <input type="text" name="email" />
    </div>
    <!-- Step 5 - Display terms of usage -->
    <div id="step5" class="stepContainer">
      <h1 class="step_title"><?php print t('Send'); ?></h1>
      <p><?php print t('Please read the following terms'); ?></p>
      <textarea readonly="readonly">Some terms placed here...</textarea>
      <input type="checkbox" id="accept" name="accept" value="1" />
      <span><?php print t('I hereby accept the terms'); ?></span>
      <br />
      <button id="submit"><?php print t('Submit'); ?></button>
      <div class="clear"></div>
    </div>

    <!-- Controls -->
    <div class="controls">
      <a href="#" id="goPrev" class="left"><img src="/<?php echo ARBO_PATH; ?>/img/rewind-32.png" /></a>
      <a href="#" id="goNext" class="right"><img src="/<?php echo ARBO_PATH; ?>/img/next-32.png" /></a>
      <div class="clear"></div>
    </div>
    <div id="tools">
      <a id="progressClone"></a>
    </div>
    <?php ;endif ?>
    <!-- Movie info, used by ajax -->
    <div id="movie_info">
      <p class="title"><?php echo $object->title.' [anmeldelse]'; ?></p>
      <p class="description"><?php echo  t('Review by').' '.$object->title.'. Beskrivelse af materialet: '.$object->abstract.'' ?></p>
      <p class="tags"><?php echo implode(', ', $object->subjects).', EasyTown, '.$object->title.', inlead, artesis'; ?></p>
      <p class="movie_name"></p>
      <p class="ac_identifier"><?php echo $ac_identifier; ?></p>
      <p class="object_id"><?php echo $object->ding_entity_id; ?></p>
    </div>
    <?php if ($user->uid != 0) : ?>
  </div>
  <div id="widget_response"></div>
</div>
<?php ;endif ?>
<?php

// If an item has a faust number, display the video reviews carousel,
// since video reviews are requested from voxb using this faust number.
if (strlen($ac_identifier) > 0) {
  require_once(ARBO_PATH . '/ding_arbo-carousel.tpl.php');
}

?>
