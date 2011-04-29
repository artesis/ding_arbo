<?php

require_once(VOXB_PATH . '/lib/VoxbBase.class.php');
require_once(ARBO_PATH . '/lib/VoxbVideoReviewRecord.class.php');

/**
 * @file
 *
 * VoxbReviews class.
 * This class handles reviews colection.
 */
class VoxbVideoReviews extends VoxbBase implements Iterator{

  private $items = array();
  private $position;

  public function __construct() {
    parent::getInstance();
    $this->position = 0;
  }


  /**
   * Fetches data from simpleXml object to array of VoxbVideoReview objects.
   *
   * @param object $voxbUserItems
   */
  public function fetch($voxbUserItems) {
    foreach ($voxbUserItems as $v) {
      if (!$v) continue;
      if ($v->reviewTitle == 'videoreview') {
        $this->items[] = new VoxbVideoReviewRecord($v);
      }
    }
  }

  /**
   * This method takes all items attributes and coverts them to an array.
   *
   * @return array
   */
  public function toArray() {
    $a = array();
    foreach ($this->items as $v) {
      $a[] = $v->toArray();
    }
    return $a;
  }

  /**
   * Iterator interface method.
   */
  public function rewind() {
    $this->position = 0;
  }

  /**
   * Iterator interface method.
   */
  public function current() {
    return $this->items[$this->position];
  }

  /**
   * Iterator interface method.
   */
  public function key() {
    return $this->position;
  }

  /**
   * Iterator interface method.
   */
  public function next() {
    ++$this->position;
  }

  /**
   * Iterator interface method.
   */
  public function valid() {
    return isset($this->items[$this->position]);
  }

  /**
   * Returns amount of items available in class collection.
   *
   * @return integer
   */
  public function getCount() {
    return count($this->items);
  }
}
