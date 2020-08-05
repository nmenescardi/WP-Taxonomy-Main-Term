<?php

/**
 * Public functions available to be used by other plugins or a theme.
 */

if (!function_exists('wp_tmt_get_posts_for_main_term')) {
  /**
   * Retrieves posts that have set a given term as primary.
   * 
   * @param int|WP_Term| $mainTerm 
   * @param string $postType 
   * @param array $args 
   */
  function wp_tmt_get_posts_for_main_term($mainTerm, $postType = 'post', $args = [])
  {
    if (is_numeric($mainTerm) && $mainTerm > 0)
      $mainTerm = get_term($mainTerm);

    if (!$mainTerm instanceof WP_Term) return [];

    $taxonomyKeys = new \WP_TMT\Core\Helpers\TaxonomyKeys($mainTerm->taxonomy);

    $args = wp_parse_args(
      $args,
      [
        'posts_per_page'          => 200,
        'post_type'               => $postType,
        'no_found_rows'           => true, // No pagination by default 
        'update_post_meta_cache'  => false,
        'update_post_term_cache'  => false,
        'meta_key'                => $taxonomyKeys->metaKey(),
        'meta_value'              => $mainTerm->term_id,
      ]
    );

    $query = new WP_Query($args);

    return $query->posts;
  }
}

if (!function_exists('wp_tmt_get_main_term_id')) {
  /**
   * Retrieves the ID of the main term saved for a given post
   *
   * @param string $taxonomy Optional. The taxonomy to get the main term ID for. Defaults to category.
   * @param null|int|WP_Post $post Optional. Post to get the main term ID for.
   *
   * @return mixed
   */
  function wp_tmt_get_main_term_id($taxonomy = 'category', $post = null)
  {
    $post = get_post($post);

    $mainTermModel = new \WP_TMT\Core\MainTerms\MainTerm($taxonomy, $post->ID);
    return $mainTermModel->getMainTerm();
  }
}

if (!function_exists('wp_tmt_get_main_term_name')) {
  /**
   * Retrieves the Name of the main term saved for a given post
   *
   * @param string $taxonomy Optional. The taxonomy to get the main term ID for. Defaults to category.
   * @param null|int|WP_Post $post Optional. Post to get the main term ID for.
   *
   * @return mixed
   */
  function wp_tmt_get_main_term_name($taxonomy = 'category', $post = null)
  {
    $term = get_term(
      wp_tmt_get_main_term_id($taxonomy, $post)
    );

    return (!is_wp_error($term) && !empty($term))
      ? $term->name
      : '';
  }
}
