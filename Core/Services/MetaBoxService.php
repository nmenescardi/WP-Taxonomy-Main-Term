<?php

namespace WP_TMT\Core\Services;

use WP_TMT\Core\MainTerms\MetaBox;

class MetaBoxService implements \Pimple\ServiceProviderInterface
{

  public function register($container)
  {
    $metaBox = new MetaBox();
    $metaBox->register();
  }
}
