<?php

use WP_TMT\Core\MainTerms\Admin;
use WP_TMT\Core\MainTerms\MainTerm;

class AdminClassTest extends WP_UnitTestCase
{

  public function setup()
  {
    parent::setUp();

    $this->subscriberUserID = $this->factory->user->create(array('role' => 'subscriber'));
    $this->editorUserID = $this->factory->user->create(array('role' => 'editor'));

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

    //Editor by default
    wp_set_current_user($this->editorUserID);
    $this->admin = new Admin;

    // Let's hardcode main term request
    $this->mainTerm = new MainTerm($this->taxonomyName, $this->postID);

    // Fake nonce
    $_REQUEST[$this->mainTerm->nonceKey()] = $_POST[$this->mainTerm->nonceKey()] = wp_create_nonce('save_main_term');
  }

  public function test_saving_OK_post_with_main_term()
  {
    // Fake main term
    $_POST[$this->mainTerm->queryVarKey()] = $this->terms[1];

    // Not equals before saving
    $this->assertNotEquals($this->mainTerm->getMainTerm(), $this->terms[1]);

    // Perform saving main term
    $this->admin->saveMainTerms($this->postID);

    // The main term has been saved properly
    $this->assertEquals($this->mainTerm->getMainTerm(), $this->terms[1]);
  }

  public function test_not_saving_the_main_term_with_non_valid_term()
  {
    // Non valid term because it is not associated with the post
    $_POST[$this->mainTerm->queryVarKey()] = $this->terms[2];

    // Perform saving main term
    $this->admin->saveMainTerms($this->postID);

    $this->assertFalse($this->mainTerm->getMainTerm());
    $this->assertNotEquals($this->mainTerm->getMainTerm(), $this->terms[2]);
  }

  public function test_subscriber_cannot_save_a_main_term()
  {
    // Test as Subscriber
    wp_set_current_user($this->subscriberUserID);

    // Fake main term
    $_POST[$this->mainTerm->queryVarKey()] = $this->terms[1];

    // This should not save the Main Term
    $this->admin->saveMainTerms($this->postID);

    $this->assertNotEquals($this->mainTerm->getMainTerm(), $this->terms[1]);
  }

  public function test_not_saving_a_main_term_when_autosave()
  {
    define('DOING_AUTOSAVE', true);

    // Fake main term
    $_POST[$this->mainTerm->queryVarKey()] = $this->terms[1];

    // This should not save the Main Term
    $this->admin->saveMainTerms($this->postID);

    $this->assertNotEquals($this->mainTerm->getMainTerm(), $this->terms[1]);
  }
}
