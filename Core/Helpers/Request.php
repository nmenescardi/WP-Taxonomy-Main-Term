<?php

namespace WP_TMT\Core\Helpers;

class Request
{

  public static function currentID()
  {
    $postID = self::postIdFromGET();

    if (!$postID && self::hasGlobalPostID())
      $postID = self::postIdFromGlobals();

    return $postID;
  }

  public static function postIdFromGET()
  {
    return filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
  }

  public static function hasGlobalPostID()
  {
    return isset($GLOBALS['post_ID']);
  }

  public static function postIdFromGlobals()
  {
    return filter_var($GLOBALS['post_ID'], FILTER_SANITIZE_NUMBER_INT);
  }

  public static function mainTermFromPOST($key)
  {
    return filter_input(
      INPUT_POST,
      $key,
      FILTER_SANITIZE_NUMBER_INT
    );
  }
}
