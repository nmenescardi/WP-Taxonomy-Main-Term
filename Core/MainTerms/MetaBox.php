<?php

namespace WP_TMT\Core\MainTerms;

use WP_TMT\Core\Concerns\RenderableTemplate;
use function WP_TMT\Core\Helpers\app;

/**
 * Class to handle WP MetaBoxes. It includes a single Meta Box for each hierarchical taxonomy for the current post.
 */
class MetaBox
{
  use RenderableTemplate;

  public function register()
  {
    add_action('add_meta_boxes', [$this, 'addMetaBox']);
  }

  public function addMetaBox($postType)
  {
    $taxonomies = app('main_term.admin')->getTaxonomies($postType);

    foreach ($taxonomies as $taxonomy) {
      add_meta_box(
        'select_main_term_' . $taxonomy->name,
        'Select Main Term for ' . $taxonomy->label,
        array($this, 'metaBoxCallback'),
        $postType,
        'side',
        'high',
        [$taxonomy]
      );
    }
  }

  public function metaBoxCallback($post, $metabox)
  {
    if (isset($metabox['args'][0]))
      $taxonomy = $metabox['args'][0];
    else
      return;

    $terms = get_the_terms($post, $taxonomy->name);

    if (empty($terms)) return;

    $mainTerm = new MainTerm($taxonomy->name, $post->ID);

    $this->viewModel = [
      'selectID'    => $mainTerm->queryVarKey(),
      'selectName'  => $mainTerm->queryVarKey(),
      'nonceAction' => 'save_main_term',
      'nonceName'   => $mainTerm->nonceKey(),
      'mainTerm'    => $mainTerm->getMainTerm(),
      'terms'       => $terms,
    ];
    $this->templatePath = app('base_templates_path') . 'main_term_metabox.php';
    $this->render();
  }
}
