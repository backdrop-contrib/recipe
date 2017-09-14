<?php

namespace Drupal\recipe\Tests;

/**
 * Tests display of nodes in recipe lists for node access.
 */
class RecipeNodeAccessTest extends \DrupalWebTestCase {

  /**
   * A user with administrative privileges.
   *
   * @var \stdClass
   */
  protected $adminUser;

  /**
   * A node title.
   *
   * @var string
   */
  protected $nodeTitle;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe node access',
      'description' => 'Tests display of nodes in recipe lists for node access.',
      'group' => 'Recipe',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // Enable modules required for testing.
    parent::setUp(array('recipe', 'node_access_test'));

    // Create and log in the admin user with Recipe content permissions.
    $this->adminUser = $this->drupalCreateUser(array(
      'create recipe content',
      'edit own recipe content',
      'administer blocks',
    ));
    $this->drupalLogin($this->adminUser);

    // Set the variable to enable private nodes with node_access_test.
    node_access_rebuild();
    variable_set('node_access_test_private', TRUE);

    // Create a test recipe node.
    $this->nodeTitle = $this->randomName(16);
    $edit = array(
      'title' => $this->nodeTitle,
      'recipe_description[' . LANGUAGE_NONE . '][0][value]' => $this->randomName(16),
      'recipe_yield' => 1,
      'private' => TRUE,
    );
    $this->drupalPost('node/add/recipe', $edit, 'Save');

    // Enable the Newest Recipes and Recipe Summary blocks.
    $edit = array(
      "blocks[recipe_recent][region]" => 'sidebar_first',
    );
    $this->drupalPost('admin/structure/block', $edit, t('Save blocks'));
  }

  /**
   * Tests node_access for nodes displayed in recipe lists.
   */
  public function testRecentRecipeBoxNodeAccess() {
    // Logout and assert that the anonymous user can't access the node.
    $this->drupalLogout();
    $this->drupalGet('node/1');
    $this->assertResponse(403);
    // Assert that a link to the test recipe can't be seen at /recipe.
    $this->drupalGet('recipe');
    $this->assertNoLink($this->nodeTitle);

    // Log in as the admin_user and set the recipe node as public.
    $this->drupalLogin($this->adminUser);
    $edit = array(
      'private' => FALSE,
    );
    $this->drupalPost('node/1/edit', $edit, 'Save');

    // Logout and assert that the anonymous user can access the node.
    $this->drupalLogout();
    $this->drupalGet('node/1');
    $this->assertResponse(200);
    // Logout and assert that two links to the test recipe can be seen at
    // /recipe. One should be from the /recipe page Recent Recipes box and the
    // other should be from the Latest Recipes block.
    $this->drupalGet('recipe');
    $this->assertLink($this->nodeTitle, 0);
    $this->assertLink($this->nodeTitle, 1);
  }

}
