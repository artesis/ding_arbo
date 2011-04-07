<?php

/**
 * @file
 *
 * Display the video reviews carousel with videos stored on YT
 */

$reviews = $obj->getReviews('videoreview');

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
          echo '<li><a href="'.$v->getLink().'" class="review_link"><img src="'.$v->getThumbnail().'" alt="" width="85" height="85" /></a></li>';
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

  drupal_add_js($inline, 'inline');

}
?>
