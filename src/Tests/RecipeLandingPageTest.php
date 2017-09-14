<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the Recipe module landing page at /recipe.
 */
class RecipeLandingPageTest extends RecipeWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe landing page',
      'description' => 'Test the content on the Recipe module landing page.',
      'group' => 'Recipe',
    );
  }

  /**
   * Tests the content displayed on the Recipe module landing page.
   */
  public function testRecipeLandingPage() {
    // While logged in as admin user, check for the "Add a new recipe" link.
    $this->drupalGet('recipe');
    $this->assertLink('Add a new recipe', 0);
    // Logout and check that the add recipe link is inaccessible.
    $this->drupalLogout();
    $this->drupalGet('recipe');
    $this->assertNoLink('Add a new recipe');
    $this->drupalLogin($this->adminUser);

    // Check for the Recent Recipe (Latest recipes) box.
    $this->drupalGet('recipe');
    $this->assertText('Latest recipes');

    // Create a recipe node.
    $node_title = $this->randomName(16);
    $edit = array(
      'type' => 'recipe',
      'title' => $node_title,
      'recipe_source' => '',
      'recipe_yield' => 1,
      'recipe_yield_unit' => '',
      'recipe_description' => array(
        'value' => '',
      ),
      'recipe_instructions' => array(
        'value' => '',
      ),
      'recipe_notes' => array(
        'value' => '',
      ),
      'recipe_preptime' => 1,
      'recipe_cooktime' => 1,
      'recipe_ingredients' => array(
        'ing' => array(),
      ),
    );
    $this->drupalCreateNode($edit);

    // Check that the recipe title is displayed.
    $this->drupalGet('recipe');
    $this->assertLink($node_title, 0);

    // Change the title of the box and the number of node titles displayed.
    $recent_recipe_title = $this->randomName(16);
    $edit = array(
      'recipe_recent_box_title' => $recent_recipe_title,
      'recipe_recent_display' => 0,
    );
    $this->drupalPost('admin/config/system/recipe', $edit, t('Save configuration'));

    // Check that the recipe title is not displayed.
    $this->drupalGet('recipe');
    $this->assertText($recent_recipe_title);
    $this->assertNoLink($node_title);

    // Disable the Recent Recipe box.
    $edit = array(
      'recipe_recent_box_enable' => FALSE,
    );
    $this->drupalPost('admin/config/system/recipe', $edit, t('Save configuration'));

    // Check that the Recent Recipe box is disabled.
    $this->drupalGet('recipe');
    $this->assertNoText($recent_recipe_title);
    $this->assertNoLink($node_title);
  }

}
