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

    // Save main term
    $mainTermModel = new MainTerm($this->taxonomyName, $this->postID);
    $mainTermModel->save($this->term->term_id);
  }

  public function test__wp_tmt_get_main_term_id__function()
  {
    $this->assertTrue(function_exists('wp_tmt_get_main_term_id'));

    $this->assertEquals(
      wp_tmt_get_main_term_id($this->taxonomyName, $this->postID),
      $this->term->term_id
    );
  }

  public function test__wp_tmt_get_main_term_name__function()
  {
    $this->assertTrue(function_exists('wp_tmt_get_main_term_name'));

    $this->assertEquals(
      wp_tmt_get_main_term_name($this->taxonomyName, $this->postID),
      $this->term->name
    );
  }

  public function test__wp_tmt_get_posts_for_main_term__function()
  {
    $this->assertTrue(function_exists('wp_tmt_get_posts_for_main_term'));

    // Create posts associated with the same term
    $term = $this->factory->term->create_and_get(array(
      'taxonomy' => $this->taxonomyName
    ));
    $allPostIDs = $this->factory->post->create_many(25, [
      'post_type' => 'post',
      'post_category' => [$term->term_id]
    ]);

    // Select a subset of posts from random positions. These will be used to compare with the subject.
    $amountOfPosts = 5;
    $randomKeys = array_rand($allPostIDs, $amountOfPosts);
    $expectedPosts = array_map(
      function ($randomKey) use ($allPostIDs, $term) {

        // Single Post ID
        $postID = $allPostIDs[$randomKey];

        // Save the main term for the selected subset.
        $mainTermModel = new MainTerm($this->taxonomyName, $postID);
        $mainTermModel->save($term->term_id);

        return $postID;
      },
      $randomKeys
    );

    // Grab only post IDs from subject.
    $postIDsFromQuery = wp_list_pluck(wp_tmt_get_posts_for_main_term($term), 'ID');
    $this->assertCount($amountOfPosts, $postIDsFromQuery);
    $this->assertEqualsCanonicalizing($expectedPosts, $postIDsFromQuery);
  }
}
