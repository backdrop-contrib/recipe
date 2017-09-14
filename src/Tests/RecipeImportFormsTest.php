<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the single and multiple recipe import forms.
 */
class RecipeImportFormsTest extends RecipeWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe import forms',
      'description' => 'Test the single and multiple recipe import forms',
      'group' => 'Recipe',
    );
  }

  /**
   * Test import a recipe in plain text format with the single import form.
   */
  public function testPlainTextSingleImport() {
    // Enter a recipe into the import form and preview it.
    $edit = array(
      'recipe_format' => 'recipe_plaintext_import',
      'recipe_import_text' => 'Salt water

Ingredients:
2 c water (cold)
1 T salt

Instructions:
Combine water and salt in a glass.

Stir.

Description:
Basic salt water.

Notes:
Do not consume!
',
    );
    $this->drupalPost('node/add/recipe/import', $edit, 'Preview');
    $this->assertText('Salt water');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Instructions:
Combine water and salt in a glass.

Stir.

Description:
Basic salt water.

Notes:
Do not consume!', 'Found recipe instructions, description, and notes.');

    // Import the recipe into a node.
    $this->drupalPost('node/add/recipe/import', $edit, 'Import');
    $this->drupalGet('node/1');
    $this->assertText('Salt water');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Combine water and salt in a glass.', 'Found the first instruction.');
    $this->assertText('Stir.', 'Found the second instruction.');
    $this->assertText('Basic salt water.', 'Found the recipe description.');
    $this->assertText('Do not consume!', 'Found the recipe notes.');
  }

  /**
   * Test import a recipe in MasterCook4 format with the single import form.
   */
  public function testMasterCook4SingleImport() {
    // Enter a recipe into the import form and preview it.
    $edit = array(
      'recipe_format' => 'recipe_mastercook4_import_single',
      'recipe_import_text' => '* Exported from MasterCook *

                     Salt water

Recipe By     : John Doe
Serving Size  : 1    Preparation Time : 0:05
Categories    :
  Amount  Measure       Ingredient -- Preparation Method
--------  ------------  --------------------------------
       2          cups  water -- cold
       1    tablespoon  salt

Combine water and salt in a glass.

Stir.

                                    - - - - - - - - - - - - - - - - - - -

NOTES : Do not consume!',
    );
    $this->drupalPost('node/add/recipe/import', $edit, 'Preview');
    $this->assertText('Salt water');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Combine water and salt in a glass.

Stir.', 'Found recipe instructions.');
    $this->assertText('Do not consume!', 'Found recipe notes.');

    // Import the recipe into a node.
    $this->drupalPost('node/add/recipe/import', $edit, 'Import');
    $this->drupalGet('node/1');
    $this->assertText('Salt water');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Combine water and salt in a glass.', 'Found the first instruction.');
    $this->assertText('Stir.', 'Found the second instruction.');
    $this->assertText('Do not consume!', 'Found the recipe notes.');
  }

  /**
   * Test import recipes in MasterCook4 format with the multiple import form.
   */
  public function testMasterCook4MultipleImport() {
    // Check for the MasterCook4 form link on the bulk import page.
    $this->drupalGet('admin/structure/recipe/import_multi');
    $this->assertLink('MasterCook4', 0);

    // Import the MasterCook4 test file using the multiple import form.
    $edit = array(
      'files[recipe_import_file]' => drupal_get_path('module', 'recipe') . '/tests/recipe_mastercook4_test.mxp',
    );
    $this->drupalPost('admin/structure/recipe/import_multi/mastercook4', $edit, t('Import'));
    $this->assertText(t('The attached file was successfully uploaded'));

    // Verify that the first recipe was imported correctly.
    $this->drupalGet('node/1');
    $this->assertText('Salt water');
    $this->assertText('John Doe', 'Found the recipe source.');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Combine water and salt in a glass.', 'Found the first instruction.');
    $this->assertText('Stir.', 'Found the second instruction.');
    $this->assertText('Do not consume!', 'Found the recipe notes.');

    // Verify that the second recipe was imported correctly.
    $this->drupalGet('node/2');
    $this->assertText('Hard-boiled eggs');
    $this->assertText('Jane Doe', 'Found the recipe source.');
    $this->assertText('2 q', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (hot)', 'Found ingredient 0 name and note.');
    $this->assertRaw('<div class="quantity-unit" property="schema:amount"> 4 </div>', 'Found ingredient 1 quantity with no unit.');
    $this->assertText('eggs', 'Found ingredient 1 name.');
    $this->assertText('Boil the water.', 'Found the first instruction.');
    $this->assertText('Put the eggs in the boiling water for 5 minutes.', 'Found the second instruction.');
    $this->assertText('Allow the eggs to cool.', 'Found the third instruction.');
    $this->assertText('Break the shells and consume.', 'Found the fourth instruction.');
  }

  /**
   * Test import recipes in recipeML format with the multiple import form.
   */
  public function testRecipeMlMultipleImport() {
    // Check for the recipeML form link on the bulk import page.
    $this->drupalGet('admin/structure/recipe/import_multi');
    $this->assertLink('recipeML', 0);

    // Import the recipeML test file using the multiple import form.
    $edit = array(
      'files[recipe_import_file]' => drupal_get_path('module', 'recipe') . '/tests/recipe_recipeml_test.xml',
    );
    $this->drupalPost('admin/structure/recipe/import_multi/recipeml', $edit, t('Import'));
    $this->assertText(t('The attached file was successfully uploaded'));

    // Verify that the first recipe was imported correctly.
    $this->drupalGet('node/1');
    $this->assertText('Salt water');
    $this->assertFieldById('edit-custom-yield', 1, 'Found the recipe yield in the custom yield form.');
    $this->assertText('Servings', 'Found the recipe yield unit.');
    $this->assertText('John Doe', 'Found the recipe source.');
    $this->assertText('2 c', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (cold)', 'Found ingredient 0 name and note.');
    $this->assertText('1 T', 'Found ingredient 1 quantity and unit.');
    $this->assertText('salt', 'Found ingredient 1 name.');
    $this->assertText('Combine water and salt in a glass.', 'Found the first instruction.');
    $this->assertText('Stir.', 'Found the second instruction.');
    $this->assertText('Do not consume!', 'Found the recipe notes.');

    // Verify that the second recipe was imported correctly.
    $this->drupalGet('node/2');
    $this->assertText('Hard-boiled eggs');
    $this->assertFieldById('edit-custom-yield', 2, 'Found the recipe yield in the custom yield form.');
    $this->assertText('Servings', 'Found the recipe yield unit.');
    $this->assertText('Jane Doe', 'Found the recipe source.');
    $this->assertText('2 q', 'Found ingredent 0 quantity and unit.');
    $this->assertText('water (hot)', 'Found ingredient 0 name and note.');
    $this->assertRaw('<div class="quantity-unit" property="schema:amount"> 4 </div>', 'Found ingredient 1 quantity with no unit.');
    $this->assertText('eggs', 'Found ingredient 1 name.');
    $this->assertText('Boil the water.', 'Found the first instruction.');
    $this->assertText('Put the eggs in the boiling water for 5 minutes.', 'Found the second instruction.');
    $this->assertText('Allow the eggs to cool.', 'Found the third instruction.');
    $this->assertText('Break the shells and consume.', 'Found the fourth instruction.');
  }

}
