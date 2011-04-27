<?php

require_once(VOXB_PATH . '/lib/VoxbBase.class.php');

/**
 * @file
 *
 * VoxB video review class.
 *
 * It's parsing data from simpleXml object received from VoxB server.
 * Creating right links to youtube video and thumbnail.
 */
class VoxbVideoReviewRecord extends VoxbBase{
  private $YTLink;
  private $YTThumbnailLink;

  public function __construct($sXml = NULL) {
    if ($sXml) {
      $this->parse($sXml);
    }
    parent::getInstance();
  }

  private function parse($sXml) {
    $this->YTLink = $sXml->reviewData;
      preg_match("~watch\?v=(.+?)$~is", $this->YTLink, $m);

      // Create the YouTube thumbnail link
      $this->YTThumbnailLink = 'http://img.youtube.com/vi/' . $m[1] . '/' . mt_rand(1, 3) . '.jpg';
  }
  /**
   * Getter function.
   */
  public function getLink() {
    return $this->YTLink;
  }

  /**
   * Getter function.
   */
  public function getThumbnail() {
    return $this->YTThumbnailLink;
  }


  /*
   * Create videoreview.
   *
   * @param string $isbn
   * @param string $review - link to youtube video file
   * @param integer $userId
   */
  public function create($faustNum, $review, $profile) {
    // check if user has already reviewed this item
    $data = $profile->getVoxbUserData($faustNum);
    if ($data && ($data['review']['title'] == 'videoreview' || !$data['review']['title'])) {
      // Update reviews
      $r = $this->update($data['voxbIdentifier'], $review);

      return $r;
    }

    $response = $this->call('createMyData', array(
      'userId' => $profile->getUserId(),
      'item' => array(
        'review' => array(
          'reviewTitle' => 'videoreview',
          'reviewData' => $review,
          'reviewType' => 'TXT'
        )
      ),
      'object' => array(
        'objectIdentifierValue' => $faustNum,
        'objectIdentifierType' => 'FAUST'
      )
    ));

    if (!$response || $response->error) {
      return FALSE;
    }
    return TRUE;
  }

  private function update($voxbId, $review) {
    $response = $this->call('updateMyData', array(
      'voxbIdentifier' => $voxbId,
      'item' => array(
        'review' => array(
          'reviewTitle' => 'videoreview',
          'reviewData' => $review,
          'reviewType' => 'TXT'
        )
      )
    ));

    if (!$response || $response->error) {
      return FALSE;
    }
    return TRUE;
  }
}
