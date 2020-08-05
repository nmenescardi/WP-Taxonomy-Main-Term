<?php

namespace WP_TMT\Core\MainTerms;

use WP_TMT\Core\Helpers\TaxonomyKeys;

/**
 * Model class to handle DB operations for a single Main Term.
 */
class MainTerm
{

  protected $taxonomyName;
  protected $postID;
  protected $mainTerm;
  protected $taxonomyKeys;

  public function __construct($taxonomyName, $postID, $taxonomyKeys = null)
  {
    $this->taxonomyName = $taxonomyName;
    $this->postID = $postID;

    $this->taxonomyKeys = $taxonomyKeys ?: new TaxonomyKeys($this->taxonomyName);
  }

  public function getMainTerm()
  {
    $this->mainTerm = \get_post_meta(
      $this->postID,
      $this->taxonomyKeys->metaKey(),
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
      $this->taxonomyKeys->metaKey(),
      $newMainTerm
    );
  }

  public function queryVarKey()
  {
    return $this->taxonomyKeys->queryVarKey();
  }

  public function nonceKey()
  {
    return $this->taxonomyKeys->nonceKey();
  }
}
