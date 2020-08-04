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
    if (!current_user_can('edit_post', $postID)) return;

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
      Request::isValidNonce($mainTerm->nonceKey(), 'save_main_term')
    ) {
      $mainTerm->save($newMainTermID);
    }
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
