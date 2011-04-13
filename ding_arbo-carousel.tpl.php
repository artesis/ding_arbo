<?php

/**
 * @file
 *
 * Display the video reviews carousel with videos stored on YT.
 */

$item = new VoxbItem();
$item->addReviewHandler('videoreview', new VoxbVideoReviews());
$item->fetchByFaust($faust_number);
$reviews = $item->getReviews('videoreview');

if ($reviews != NULL && $reviews->getCount() > 0) {

?>
  <style type="text/css">@import url("<?php echo ARBO_PATH; ?>/css/arbo-carousel.css");</style>
  <div id="slider">
    <button class="prev" style="float:left;"><<</button>
    <button class="next" style="float: right;">>></button>
    <div id="mycarousel">
      <ul>
      <?php
        foreach ($reviews as $v) {
        	$a = explode('v=', $v->getLink());
          echo l(
            '<img src="'.$v->getThumbnail().'" alt="" width="85" height="85" />',
            'http://artesis/arbo/ajax/carousel/youtube?video=' . $a[1],
            array(
              'html' => TRUE,
              'attributes' => array(
                'class' => array(
                  'use-ajax'))));
        }
      ?>
      </ul>
      <div class="clear"></div>
    </div>
  </div>

  <div id="yt_player">
    <p class="close">
      <a href="javascript: void();"><img src="<?php echo ARBO_PATH; ?>/img/cancel-on.png" alt="" /></a>
    </p>
  </div>

<?php 

  $inline = 'jQuery(document).ready(function(){
    jQuery("#mycarousel").jCarouselLite({
      btnNext: ".next",
      btnPrev: ".prev",
      circular: false
    });
  });';

  drupal_add_js('misc/jquery.form.js');
  drupal_add_js($inline, 'inline');

}
?>
