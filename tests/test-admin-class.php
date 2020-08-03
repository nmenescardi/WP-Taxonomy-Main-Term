<?php

use WP_TMT\Core\MainTerms\Admin;
use WP_TMT\Core\MainTerms\MainTerm;

class AdminClassTest extends WP_UnitTestCase
{

  public function setup()
  {
    parent::setUp();

    $this->taxonomyName = 'category';
    $this->terms = $this->factory->term->create_many(3, array(
      'taxonomy' => $this->taxonomyName
    ));

    $this->postID = $this->factory->post->create([
      'post_type' => 'post',
      'post_category' => [
        $this->terms[0],
        $this->terms[1]
      ]
    ]);

    $this->admin = new Admin;
  }

  public function test_saving_OK_post_with_main_term()
  {
    // Let's hardcode main term request
    $mainTerm = new MainTerm($this->taxonomyName, $this->postID);

    // Fake main term
    $_POST[$mainTerm->queryVarKey()] = $this->terms[1];

    // Fake nonce
    $_REQUEST[$mainTerm->nonceKey()] = $_POST[$mainTerm->nonceKey()] = wp_create_nonce('save_main_term');

    // Not equals before saving
    $this->assertNotEquals($mainTerm->getMainTerm(), $this->terms[1]);

    // Perform saving main term
    $this->admin->saveMainTerms($this->postID);

    // The main term has been saved properly
    $this->assertEquals($mainTerm->getMainTerm(), $this->terms[1]);
  }
}
