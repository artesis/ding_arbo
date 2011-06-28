<?php
/**
 * @file
 *
 * Display the video reviews carousel with videos stored on YT.
 */

$item = new VoxbItem();
$item->addReviewHandler('videoreview', new VoxbVideoReviews());
$item->fetchByFaust($object->localId);
$reviews = $item->getReviews('videoreview');

if ($reviews && $reviews->getCount() > 0) {
?>

  <h3 style="text-align: left;"><?php print t('Videoreviews'); ?></h3>
  <div id="slider">
    <a class="buttons prev" href="#"></a>
    <div class="viewport">
      <ul class="overview">
      <?php
        foreach ($reviews as $v) {
          $a = explode('v=', $v->getLink());
          echo '<li><a href="/arbo/ajax/carousel/youtube/' . $a[1] . '" class="use-ajax"><img src="'.$v->getThumbnail().'" alt="" width="85" height="85" /></a></li>';
        }
      ?>
      </ul>
    </div>
    <a class="buttons next" href="#"></a>
  </div>

<?php } ?>
