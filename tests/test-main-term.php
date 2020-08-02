<?php

use WP_TMT\Core\MainTerms\MainTerm;

class MainTermTest extends WP_UnitTestCase
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

    $this->postID_WithoutValidTerm = $this->factory->post->create([
      'post_type' => 'post',
      'post_category' => [-1]
    ]);


    $this->invalidPostID = $this->invalidTermID = 'invalidPostID';
    $this->invalidTaxonomyName = 'invalidTaxonomyName';
  }

  public function test_save_returns_false_on_not_valid_post()
  {
    $mainTerm = new MainTerm($this->taxonomyName, $this->invalidPostID);

    $this->assertFalse($mainTerm->save($this->term->term_id));
  }

  public function test_save_returns_term_id_with_valid_post()
  {
    $mainTerm = new MainTerm($this->taxonomyName, $this->postID);

    $metaID = $mainTerm->save($this->term->term_id);

    // Check metaID is valid
    $this->assertInternalType('int', $metaID);
    $this->assertGreaterThan(0, $metaID);

    // Grab stored main term using metaID and check it's equal to the original value
    $meta = get_metadata_by_mid('post', $metaID);
    $this->assertEquals($this->term->term_id, $meta->meta_value);
  }

  public function test_main_term_false_with_invalid_post()
  {
    $mainTerm = new MainTerm($this->taxonomyName, $this->invalidPostID);

    $this->assertFalse($mainTerm->getMainTerm());
  }

  public function test_main_term_false_with_invalid_taxonomy()
  {
    $mainTerm = new MainTerm($this->invalidTaxonomyName, $this->postID);

    $this->assertFalse($mainTerm->getMainTerm());
  }

  public function test_main_term_false_with_post_without_valid_term()
  {
    $mainTerm = new MainTerm($this->taxonomyName, $this->postID_WithoutValidTerm);

    $this->assertFalse($mainTerm->getMainTerm());
  }

  public function test_main_term_returns_proper_term_id()
  {
    $mainTerm = new MainTerm($this->taxonomyName, $this->postID);
    $mainTerm->save($this->term->term_id);

    $this->assertEquals($mainTerm->getMainTerm(), $this->term->term_id);
  }
}
