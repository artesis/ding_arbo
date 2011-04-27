<div class="videoReviewsContainer">
  <h3><?php print t('Videoreviews'); ?></h3>
  <center>
    <?php
    // get faust number
    $ac_identifier = explode('|', $object->record['ac:identifier'][''][0]);
    $faust_number = $ac_identifier[0];
    require_once(ARBO_PATH . '/ding_arbo-carousel.tpl.php');

    if ($user->uid != 0 && $profile->isAbleToReview($faust_number) && $data['review']['title'] == NULL) : ?>
    <div class="addVideoReviewContainer" style="margin-top: 15px;">
      <h1 id="arbo_review"><?php echo l(t('Make your own videoreview'), 'arbo/ajax/widget/' . $faust_number, array('attributes' => array('class' => array('use-ajax')))); ?></h1>
    </div>
    <?php ;endif ?>
  </center>
</div>
