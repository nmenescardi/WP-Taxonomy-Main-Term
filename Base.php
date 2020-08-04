<?php

namespace WP_TMT;

use WP_TMT\Core\DependencyInjection\Container;

/**
 * Main class to init the App.
 */
class Base
{
  const VERSION = '0.0.1';

  const DOMAIN = 'wp-taxonomy-main-term';

  protected $container;

  public function __construct()
  {
    $this->initContainer();
  }

  protected function initContainer()
  {
    $this->container = Container::getInstance([
      'base_domain'         => self::DOMAIN,
      'base_version'        => self::VERSION,
      'base_path'           => plugin_dir_path(__FILE__),
      'base_relative_path'  => basename(plugin_dir_path(__FILE__)),
      'base_url'            => plugin_dir_url(__FILE__),
      'base_style_handler'  => 'base-general-styles',
      'base_style_url'      => trailingslashit(plugin_dir_url(__FILE__)) . 'assets/inc/css/',
      'base_script_handler' => 'base-general-scripts',
      'base_script_url'     => trailingslashit(plugin_dir_url(__FILE__)) . 'assets/inc/js/',
      'base_acf_json_path'  => plugin_dir_path(__FILE__) . 'acf',
      'base_templates_path' => trailingslashit(plugin_dir_path(__FILE__)) . 'templates/',
    ]);
  }

  public function load()
  {
    $this->container->registerServices([
      \WP_TMT\Core\Services\MainTermService::class,
      \WP_TMT\Core\Services\MetaBoxService::class,
    ]);
  }
}
