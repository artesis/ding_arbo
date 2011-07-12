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
      <h1 id="arbo-review"><?php echo l('<input type="button" class="form-submit rounded-corners" value="' . t('Make your own videoreview') . '">', 'arbo/ajax/widget/' . $object->id, array('attributes' => array('class' => array('use-ajax')), 'html' => TRUE)); ?></h1>
    </div>
    <?php } ?>
  </center>
</div>
