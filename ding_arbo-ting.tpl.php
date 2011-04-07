<div class="videoReviewsContainer">
  <h3><?php print t('Videoreviews'); ?></h3>
  <center>
    <div class="arboContainer">
      <?php require_once(ARBO_PATH . '/ding_arbo-widget.tpl.php'); ?>
    </div>
    <?php if ($user->uid != 0 && $profile->isAbleToReview($ac_identifier)) : ?>
    <div class="addVideoReviewContainer" style="margin-top: 15px;">
      <h1 id="arbo_review"><a href="javascript: void(0);"><input type="button" value="<?php print t('Make your own videoreview'); ?>" class="form-submit"/></a></h1>
    </div>
    <?php ;endif ?>
  </center>
</div>
