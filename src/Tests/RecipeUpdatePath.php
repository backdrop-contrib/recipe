<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the update path for Recipe 7.x-1.3 to 7.x-2.x.
 *
 * @see \UpdatePathTestCase
 */
class RecipeUpdatePath extends \UpdatePathTestCase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe update tests',
      'description' => 'Tests the update path for Recipe 7.x-1.3 to 7.x-2.x.',
      'group' => 'Recipe',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // Load the database dump files.
    $this->databaseDumpFiles = array(
      drupal_get_path('module', 'simpletest') . '/tests/upgrade/drupal-7.bare.standard_all.database.php.gz',
      drupal_get_path('module', 'recipe') . '/tests/upgrade/drupal-7.recipe.database.php',
    );
    parent::setUp();

    $this->uninstallModulesExcept(array(
      'field',
      'field_sql_storage',
      'text',
      'number',
      'recipe',
    ));
  }

  /**
   * Tests the Recipe field migration.
   */
  public function testRecipeFieldMigration() {
    // Perform the update.
    $this->assertTrue($this->performUpgrade(), 'The update was completed successfully.');

    // Check for the migrated recipe fields.
    $node = node_load(1);
    $this->assertEqual($node->recipe_description[LANGUAGE_NONE][0]['value'], 'Basic salt water.', 'The recipe description was migrated.');
    $this->assertEqual($node->recipe_description[LANGUAGE_NONE][0]['format'], 'filtered_html', 'The recipe description text format was set to filtered_html.');
    $this->assertEqual($node->recipe_instructions[LANGUAGE_NONE][0]['value'], "Combine water and salt in a glass.\r\n\r\nStir.", 'The recipe instructions was migrated.');
    $this->assertEqual($node->recipe_instructions[LANGUAGE_NONE][0]['format'], 'filtered_html', 'The recipe instructions text format was set to filtered_html.');
    $this->assertEqual($node->recipe_notes[LANGUAGE_NONE][0]['value'], "Do not consume!", 'The recipe notes was migrated.');
    $this->assertEqual($node->recipe_notes[LANGUAGE_NONE][0]['format'], 'filtered_html', 'The recipe notes text format was set to filtered_html.');
    $this->assertEqual($node->recipe_source[LANGUAGE_NONE][0]['value'], "John Doe", 'The recipe source was migrated.');
    $this->assertEqual($node->recipe_source[LANGUAGE_NONE][0]['format'], 'filtered_html', 'The recipe source text format was set to filtered_html.');
    $this->assertEqual($node->recipe_prep_time[LANGUAGE_NONE][0]['value'], 1, 'The recipe preparation time was migrated.');
    $this->assertEqual($node->recipe_cook_time[LANGUAGE_NONE][0]['value'], 2, 'The recipe cooking time was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][0]['iid'], 1, 'The first recipe ingredient reference was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][0]['quantity'], 2, 'The first recipe ingredient quantity was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][0]['unit_key'], 'cup', 'The first recipe ingredient unit_key was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][0]['note'], 'cold', 'The first recipe ingredient note was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][1]['iid'], 2, 'The second recipe ingredient reference was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][1]['quantity'], 1, 'The second recipe ingredient quantity was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][1]['unit_key'], 'tablespoon', 'The second recipe ingredient unit_key was migrated.');
    $this->assertEqual($node->recipe_ingredient[LANGUAGE_NONE][1]['note'], '', 'The second recipe ingredient note was migrated.');
  }

}
