<?php

namespace WP_TMT\Core\Helpers;

/**
 * Helper class to handle the keys used for each Taxonomy.
 */
class TaxonomyKeys
{

  protected $taxonomyName;
  protected $baseKey;

  public function __construct($taxonomyName)
  {
    $this->taxonomyName = $taxonomyName;

    $this->baseKey = 'wp_tmt_main_' . $this->taxonomyName;
  }

  public function metaKey()
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
