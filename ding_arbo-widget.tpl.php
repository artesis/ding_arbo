<?php
/**
 * @file
 *
 * ARBO widget recorder implementation.
 *
 * Displays a multi-step widget to place own video review to a specific item,
 * fetched from ting.
 *
 * All requests are sent and processed at red5 installation.
 */

?>

<div id="arbo-widget">
  <div id="widget-block">
    <!-- This displays the current progress in the wizard -->
    <div id="progress"></div>
    <div class="clearfix"></div>
    <br />

    <!-- Contains all the steps -->
    <!-- Step 1 - Record -->
    <div id="step1" class="step-container">
      <h1 class="step-title">Optag</h1>
      <p><?php print t('Press the record button to record your review'); ?></p>
      <div class="record-controls">
        <a href="#" id="record"><img src="/<?php echo ARBO_PATH; ?>/img/record-32.png" /></a>
        <a href="#" id="stop" style="display:none;"><img src="/<?php echo ARBO_PATH; ?>/img/stop-32.png" /></a>
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
          <param name="FlashVars" value="streamFileName=<?php echo $video_filename; ?>" />
          <embed src="/<?php echo ARBO_PATH; ?>/swf/ARBO.swf" quality="best" bgcolor="#869ca7"
            width="500" height="385" name="ARBO" align="middle"
            play="true" loop="false" quality="high" allowNetworking="true"
            allowScriptAccess="always" type="application/x-shockwave-flash"
            pluginspage="http://www.adobe.com/go/getflashplayer"
            FlashVars="streamFileName=<?php echo $video_filename; ?>">
          </embed>
        </object>
      </div>
      <div class="clearfix"></div>
    </div>
    <!-- Step 2 - View recorded video -->
    <div id="step2" class="step-container">
      <h1 class="step-title"><?php print t('Approve'); ?></h1>
      <p><?php print t('Look your video through and press the green button to proceed'); ?></p> 
      <div id="scrubber">
        <a  
         href="<?php echo variable_get('arbo_flv_server_addr', FLV_ADDR) . '/' . $video_filename . '.flv'; ?>"
         style="display:block;width:500px;height:350px"  
         id="player"> 
        </a>
      </div>
      <p><?php print t('Tip: you can retry by pressing the back-button'); ?></p>
      <div class="clearfix"></div>
    </div>
    <!-- Step 3 - Tag/rate functionality -->
    <div id="step3" class="step-container">
      <h1 class="step-title"><?php print t('Rate/Tag'); ?></h1>
        <h3><?php print t('Tags'); ?></h3>
        <div class="tags-container">
          <div class="record-tag-highlight">
          <?php 
            foreach ($voxb_item->getTags() as $v) {
              echo theme('voxb_tag_record', array('tag_name' => $v->getName()));
            }
          ?>
          </div>
          <div class="clearfix">&nbsp;</div>
          <?php 
            if (($user->uid != 0 && $profile->isAbleToTag($faust_number))) {
              echo drupal_render(drupal_get_form('ding_voxb_tag_form', $faust_number));
            } 
          ?>
          <div class="clearfix">&nbsp;</div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="ratings-container">
          <h3><?php print t('Ratings'); ?></h3>
            <?php 
              $rating = $voxb_item->getRating();
              $rating = intval($rating / 20);
            ?>
            <?php if ($user->uid != 0) : ?>
            <div class="add-rating-container">
              <div class="user-rate">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                <div href="/voxb/ajax/rating/<?php echo $faust_number . "/" . $i; ?>" class="<?php echo ($profile->isAbleToRate($faust_number) ? 'use-ajax' : ''); ?> rating <?php echo ($i <= $rating ? 'star-on' : 'star-off'); ?>"></div>
                <?php ;endfor ?>
              </div>
              <?php ;endif ?>
            </div>
            <?php
              echo '<span class="rating-count-span">(<span class="rating-votesnumber">' . (($voxb_item->getRatingCount() > 0) ? $voxb_item->getRatingCount() : '0') . '</span>)</span>';
            ?>
            <div class="ajax-anim">&nbsp;</div>
            <div class="clearfix"></div>
        </div>
      <div class="clearfix"></div>
    </div>
    <!-- Step 4 - Mail confirmation -->
    <div id="step4" class="step-container">
      <h1 class="step-title">Confirm</h1>
      <p><?php print t('We would like to confirm you when your video review is processed and available, please type your email address below'); ?></p>
      <input type="text" name="email" />
    </div>
    <!-- Step 5 - Display terms of usage -->
    <div id="step5" class="step-container">
      <h1 class="step-title"><?php print t('Send'); ?></h1>
      <p><?php print t('Please read the following terms'); ?></p>
      <?php echo drupal_render(drupal_get_form('ding_arbo_review_form', $faust_number, $video_filename, $ting_id)); ?>
      <div class="clearfix"></div>
    </div>

    <!-- Controls -->
    <div class="controls">
      <?php
        echo l(
          '<img src="/'.ARBO_PATH.'/img/rewind-32.png" />',
          'arbo/ajax/widget/step/1',
           array(
             'html' => TRUE,
             'attributes' => array('class' => array('left'), 'id' => array('go-prev'))));
      ?>
      
      <?php
        echo l(
          '<img src="/'.ARBO_PATH.'/img/next-32.png" />',
          'arbo/ajax/widget/step/2',
           array(
             'html' => TRUE,
             'attributes' => array('class' => array('right'), 'id' => array('go-next'))));
      ?>
      <div class="clearfix"></div>
    </div>
    <div id="tools">
      <a id="progress-clone"></a>
    </div>
  </div>
</div>
