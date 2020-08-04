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
    if (isset($_POST[$key]) && is_numeric($_POST[$key]))
      return intval($_POST[$key]);
  }

  public static function isValidNonce($nonceKey, $nonceAction)
  {
    if (isset($_REQUEST[$nonceKey]))
      return is_numeric(wp_verify_nonce($_REQUEST[$nonceKey], $nonceAction));

    return false;
  }
}
