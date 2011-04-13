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

if ($user->uid != 0) : ?>

<div id="arbo_widget">
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
      <div class="clear"></div>
    </div>
    <!-- Step 2 - View recorded video -->
    <div id="step2" class="stepContainer">
      <h1 class="step_title"><?php print t('Approve'); ?></h1>
      <p><?php print t('Look your video through and press the green button to proceed'); ?></p> 
      <div id="scrubber">
        <a  
         href="<?php echo variable_get('arbo_flv_server_addr', FLV_ADDR) . '/' . $video_filename . '.flv'; ?>"
         style="display:block;width:500px;height:350px"  
         id="player"> 
        </a>
      </div>
      <p><?php print t('Tip: you can retry by pressing the back-button'); ?></p>
      <div class="clear"></div>
    </div>
    <!-- Step 3 - Tag/rate functionality -->
    <div id="step3" class="stepContainer">
      <h1 class="step_title"><?php print t('Rate/Tag'); ?></h1>
      <h3><?php print t('Tags'); ?></h3>
	    <div class="recordTagHighlight">
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
		  <div class="arbo-ratingsContainer">
		    <h3><?php print t('Ratings'); ?></h3>
		    <div class="arbo-ratingStars">
		      <?php 
		        $rating = $voxb_item->getRating();
		        $rating = intval($rating / 20);
		        for ($i = 1; $i <= 5; $i++) {
		          echo '<div class="rating ' . ($i <= $rating ? 'star-on' : 'star-off') . '"></div>';
		        }
		        if ($voxb_item->getRatingCount() > 0) {
		          echo '<span class="ratingCountSpan">(<span class="ratingVotesNumber">' . $voxb_item->getRatingCount().'</span>)</span>';
		        }
		      ?>
		    </div>
		    <?php if ($user->uid != 0 && $profile->isAbleToRate($faust_number)) : ?>
		      <div class="addRatingContainer">
		        <?php print t('Please rate this object'); ?><br />
		        <div class="arbo-userRate">
		          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/1" class="use-ajax rating star-off"></div>
		          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/2" class="use-ajax rating star-off"></div>
		          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/3" class="use-ajax rating star-off"></div>
		          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/4" class="use-ajax rating star-off"></div>
		          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/5" class="use-ajax rating star-off"></div>
		        </div>
		      </div>
		      <p class="ajax_message"><?php echo t('Thank you for contributing.'); ?></p>
		    <?php ;endif ?>
		  </div>
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
      <?php echo drupal_render(drupal_get_form('ding_arbo_review_form', $faust_number, $video_filename)); ?>
      <div class="clear"></div>
    </div>

    <!-- Controls -->
    <div class="controls">
      <?php
        echo l(
          '<img src="/'.ARBO_PATH.'/img/rewind-32.png" />',
          'arbo/ajax/widget/step/1',
           array(
             'html' => TRUE,
             'attributes' => array('class' => array('left'), 'id' => array('goPrev'))));
      ?>
      
      <?php
        echo l(
          '<img src="/'.ARBO_PATH.'/img/next-32.png" />',
          'arbo/ajax/widget/step/2',
           array(
             'html' => TRUE,
             'attributes' => array('class' => array('right'), 'id' => array('goNext'))));
      ?>
      <div class="clear"></div>
    </div>
    <div id="tools">
      <a id="progressClone"></a>
    </div>
    <?php ;endif ?>
    <?php if ($user->uid != 0) : ?>
  </div>
</div>
<?php ;endif ?>
