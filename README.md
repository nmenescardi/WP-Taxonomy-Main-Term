# WP Taxonomy Main Term

WordPress plugin that allows to select a primary (main) term for taxonomies related with posts (and CPTs).

## Usage

1. Clone repo or download zip file inside the WordPress `plugins` folder.
1. Run `composer install` inside the root plugin folder to install dependencies. More about [Composer](https://getcomposer.org/download/)
1. Activate plugin.

## Unit Tests

Follow [WordPress docs](https://make.wordpress.org/cli/handbook/misc/plugin-unit-tests/#running-tests-locally) to run unit tests locally.

## Functions

There are some public functions to be used on a theme or other plugins:

1. `wp_tmt_get_posts_for_main_term`: To fetch posts that have set a specific term as primary. Usage:

```php
$term_to_query = 21; // Term ID. It could be a WP_Term instead.
$posts = wp_tmt_get_posts_for_main_term($term_to_query);

// For a specific CPT
$posts = wp_tmt_get_posts_for_main_term($term_to_query, 'cpt_key');

// Using WP_Query args
$posts = wp_tmt_get_posts_for_main_term(
  $term_to_query,
  'post',
  ['posts_per_page' => -1]
);
```

2. `wp_tmt_get_main_term_id`: Get the main term **ID** for a given taxonomy and post

```php
$main_term_id = wp_tmt_get_main_term_id($taxonomy_name, $post_id);
```

3. `wp_tmt_get_main_term_name` Get the main term **Name** for a given taxonomy and post

```php
$main_term_name = wp_tmt_get_main_term_name($taxonomy_name, $post_id);
```
