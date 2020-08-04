<?php

namespace WP_TMT\Core\MainTerms;

/**
 * Model class to handle DB operations for a single Main Term.
 */
class MainTerm
{

  protected $taxonomyName;
  protected $postID;
  protected $mainTerm;
  protected $baseKey;

  public function __construct($taxonomyName, $postID)
  {
    $this->taxonomyName = $taxonomyName;
    $this->postID = $postID;

    $this->baseKey = 'wp_tmt_main_' . $this->taxonomyName;
  }

  public function getMainTerm()
  {
    $this->mainTerm = \get_post_meta(
      $this->postID,
      $this->metaKey(),
      true
    );

    return
      $this->isMainTermValid()
      ? (int) $this->mainTerm
      : false;
  }

  protected function isMainTermValid()
  {
    return in_array(
      (int) $this->mainTerm,
      \wp_list_pluck($this->terms(), 'term_id'),
      true
    );
  }

  protected function terms()
  {
    return
      is_array($terms = \get_the_terms($this->postID, $this->taxonomyName))
      ? $terms
      : [];
  }

  public function save($newMainTerm)
  {
    return \update_post_meta(
      $this->postID,
      $this->metaKey(),
      $newMainTerm
    );
  }

  protected function metaKey()
  {
    return $this->baseKey . '_meta';
  }

  public function queryVarKey()
  {
    return $this->baseKey . '_term';
  }

  public function nonceKey()
  {
    return $this->baseKey . '_nonce';
  }
}
