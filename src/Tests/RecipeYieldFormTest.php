<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the custom yield form in a recipe node.
 */
class RecipeYieldFormTest extends RecipeWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe yield form',
      'description' => 'Test the custom yield form in a recipe node.',
      'group' => 'Recipe',
    );
  }

  /**
   * Tests the custom yield form functionality.
   *
   * This test only verifies that the form is capable of altering the yield and
   * ingredient quantities.  Issues with values returned by ingredient quantity
   * conversion can be tested in RecipeUnitTestCase.
   */
  public function testRecipeYieldForm() {
    // Create a recipe node.
    $node_title = $this->randomName(16);
    $yield = 10;
    $quantity = 2;
    $unit_key = 'cup';
    $ingredient_name = $this->randomName(16);
    $edit = array(
      'type' => 'recipe',
      'title' => $node_title,
      'recipe_source' => '',
      'recipe_yield' => $yield,
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
        'ing' => array(
          0 => array(
            'quantity' => $quantity,
            'unit_key' => $unit_key,
            'name' => $ingredient_name,
            'note' => '',
            'weight' => 0,
          ),
        ),
      ),
    );
    $this->drupalCreateNode($edit);

    // Go to the recipe node and verify the yield and quantity values.
    $this->drupalGet('node/1');
    $this->assertFieldById('edit-custom-yield', $yield, 'Found the recipe yield in the custom yield form.');
    $this->assertText(format_string('@quantity @unit', array('@quantity' => $quantity, '@unit' => $this->unitList[$unit_key]['abbreviation'])), 'Found the recipe quantity.');

    // Use the custom yield form to halve the yield and check for new values.
    $this->drupalPost(NULL, NULL, 'Halve');
    $this->assertFieldById('edit-custom-yield', $yield / 2, 'Found the halved recipe yield in the custom yield form.');
    $this->assertText(format_string('@quantity @unit', array('@quantity' => $quantity / 2, '@unit' => $this->unitList[$unit_key]['abbreviation'])), 'Found the halved recipe quantity.');

    // Use the custom yield form to reset the values.
    $this->drupalPost(NULL, NULL, 'Reset');
    $this->assertFieldById('edit-custom-yield', $yield, 'Found the recipe yield in the custom yield form.');
    $this->assertText(format_string('@quantity @unit', array('@quantity' => $quantity, '@unit' => $this->unitList[$unit_key]['abbreviation'])), 'Found the recipe quantity.');

    // Use the custom yield form to double the yield and check for new values.
    $this->drupalPost(NULL, NULL, 'Double');
    $this->assertFieldById('edit-custom-yield', $yield * 2, 'Found the doubled recipe yield in the custom yield form.');
    $this->assertText(format_string('@quantity @unit', array('@quantity' => $quantity * 2, '@unit' => $this->unitList[$unit_key]['abbreviation'])), 'Found the doubled recipe quantity.');

    // Use the custom yield form to triple the yield and check for new values.
    $edit = array('custom_yield' => $yield * 3);
    $this->drupalPost(NULL, $edit, 'Change');
    $this->assertFieldById('edit-custom-yield', $yield * 3, 'Found the tripled recipe yield in the custom yield form.');
    $this->assertText(format_string('@quantity @unit', array('@quantity' => $quantity * 3, '@unit' => $this->unitList[$unit_key]['abbreviation'])), 'Found the tripled recipe quantity.');
  }

}
