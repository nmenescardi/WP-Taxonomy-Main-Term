<?php
/*
Plugin Name: WP Taxonomy Main Term
Description: Allows to select a primary (main) term for taxonomies related with posts (and CPTs).
Author: Nicolas Menescardi
Version: 0.0.1
Text Domain: wp-taxonomy-main-term
*/

require_once 'vendor/autoload.php';
require_once 'Base.php';

add_action('plugins_loaded', array(new WP_TMT\Base, 'load'));
