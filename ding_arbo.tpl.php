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
    
    if ($profile && $profile->isAbleToReview($object->localId) && !isset($acted['review']['title']) && !isset($acted['videoreview']['title'])) { ?>
    <div class="add-video-review-container" style="margin-top: 15px;">
      <h1 id="arbo-review"><?php echo l('<button class="form-submit">'.t('Make your own videoreview').'</button>', 'arbo/ajax/widget/' . $object->id, array('attributes' => array('class' => array('use-ajax')), 'html' => TRUE)); ?></h1>
    </div>
    <?php } ?>
  </center>
</div>
