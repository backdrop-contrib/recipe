<?php

namespace Drupal\recipe\Tests;

/**
 * Provides a base class for testing the functionality of the Recipe module.
 */
abstract class RecipeWebTestBase extends \DrupalWebTestCase {

  /**
   * A user with administrative privileges.
   *
   * @var \stdClass
   */
  protected $adminUser;

  /**
   * A multidimentional array containing Recipe's ingredient units.
   *
   * @var array
   */
  protected $unitList;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // Enable modules required for testing.
    parent::setUp(array(
      'recipe',
      'recipe_plaintext',
      'recipe_html',
      'recipe_recipeML',
      'recipe_mastercook4',
      'recipe_test',
    ));

    // Create and log in the admin user with Recipe content permissions.
    $this->adminUser = $this->drupalCreateUser(array(
      'create recipe content',
      'edit any recipe content',
      'import recipes',
      'export recipes',
      'administer site configuration',
      'administer blocks',
    ));
    $this->drupalLogin($this->adminUser);

    // Populate the unit list.
    $this->unitList = recipe_get_units();
  }

}
