<?php

/**
 * Public functions available to be used by other plugins or a theme.
 */

if (!function_exists('wp_tmt_get_main_term_id')) {
  /**
   * Retrieves the ID of the saved main term for a given post
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
