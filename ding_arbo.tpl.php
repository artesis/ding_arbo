<?php
/**
 * @file
 * 
 * Inclusion on arbo recorder stuff
 */
?>

<div class="video-reviews-container">
  <center>
    <?php
    require_once(ARBO_PATH . '/ding_arbo-carousel.tpl.php');
    $profile = unserialize($_SESSION['voxb']['profile']);
    if ($profile) {
      $acted = $profile->getVoxbUserData($object->localId);
    }
    
    if ($profile && $profile->isAbleToReview($object->localId) && $acted['review']['title'] == '' && !isset($acted['videoreview']['title'])) { ?>
    <div class="add-video-review-container" style="margin-top: 15px;">
      <h1 id="arbo-review"><input href="/arbo/ajax/widget/<?php echo $object->id; ?>" type="submit" class="form-submit rounded-corners use-ajax" value="<?php echo t('Make your own videoreview'); ?>" /></h1>
    </div>
    <?php } ?>
  </center>
</div>
