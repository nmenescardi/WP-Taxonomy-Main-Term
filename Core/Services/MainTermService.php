<?php

namespace WP_TMT\Core\Services;

use WP_TMT\Core\MainTerms\Admin;

/**
 * Init Admin hooks and add a single instance into the container.
 */
class MainTermService implements \Pimple\ServiceProviderInterface
{

  public function register($container)
  {
    $admin = new Admin();
    $admin->register();

    $container['main_term.admin'] = function ($container) use ($admin) {
      return $admin;
    };
  }
}
