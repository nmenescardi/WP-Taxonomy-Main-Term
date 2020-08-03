<?php

use WP_TMT\Core\MainTerms\MainTerm;

class PublicFunctionsTest extends WP_UnitTestCase
{

  public function setup()
  {
    parent::setUp();

    $this->taxonomyName = 'category';
    $this->term = $this->factory->term->create_and_get(array(
      'taxonomy' => $this->taxonomyName
    ));

    $this->postID = $this->factory->post->create([
      'post_type' => 'post',
      'post_category' => [$this->term->term_id]
    ]);
  }

  public function test__wp_tmt_get_main_term_id__function()
  {
    $this->assertTrue(function_exists('wp_tmt_get_main_term_id'));

    // Save main term
    $mainTermModel = new MainTerm($this->taxonomyName, $this->postID);
    $mainTermModel->save($this->term->term_id);

    $this->assertEquals(
      wp_tmt_get_main_term_id($this->taxonomyName, $this->postID),
      $this->term->term_id
    );
  }
}
