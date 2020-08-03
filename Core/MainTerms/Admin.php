<?php

namespace WP_TMT\Core\MainTerms;

use WP_TMT\Core\Helpers\Request;

class Admin
{

  protected $postID;

  public function register()
  {
    add_action('save_post', [$this, 'saveMainTerms']);
  }

  public function saveMainTerms($postID)
  {
    $this->postID = $postID;

    foreach ($this->getTaxonomies() as $taxonomy) {
      $this->saveMainTerm($taxonomy);
    }
  }

  protected function saveMainTerm($taxonomy)
  {
    $mainTerm = new MainTerm($taxonomy->name, $this->postID);

    $newMainTermID = Request::mainTermFromPOST(
      $mainTerm->queryVarKey()
    );

    if (
      $newMainTermID &&
      $this->isValidNonce($mainTerm->nonceKey())
    ) {
      $mainTerm->save($newMainTermID);
    }
  }

  protected function isValidNonce($nonceKey)
  {
    if (isset($_REQUEST[$nonceKey]))
      return is_numeric(wp_verify_nonce($_REQUEST[$nonceKey], 'save_main_term'));

    return false;
  }

  public function getTaxonomies($postType = null)
  {
    if (!$postType) {
      $this->postID ?: Request::currentID();
      $postType = \get_post_type($this->postID);
    }

    $allTaxonomies = \get_object_taxonomies(
      $postType,
      'objects'
    );

    // Return only hierarchical taxonomies
    return array_filter($allTaxonomies, function ($taxonomy) {
      return (bool) $taxonomy->hierarchical;
    });
  }
}
