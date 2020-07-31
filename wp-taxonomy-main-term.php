<?php
/*
Plugin Name: WP Taxonomy Main Term
Description: Plugin to select a primary (main) taxonomy term for a post.
Author: Nicolas Menescardi
Version: 0.0.1
Text Domain: wp-taxonomy-main-term
*/

require_once 'vendor/autoload.php';
require_once 'Base.php';

add_action('plugins_loaded', array(new WP_TMT\Base, 'load'));

