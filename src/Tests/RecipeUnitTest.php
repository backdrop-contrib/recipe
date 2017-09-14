<?php

namespace Drupal\recipe\Tests;

/**
 * Tests isolated Recipe module functions.
 *
 * @see \DrupalUnitTestCase
 */
class RecipeUnitTest extends \DrupalUnitTestCase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe unit tests',
      'description' => 'Test that Recipe functions work properly.',
      'group' => 'Recipe',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    drupal_load('module', 'recipe');
    parent::setUp();
  }

  /**
   * Test ingredient quantity conversion functions.
   */
  public function testIngredientQuantityConversion() {
    $quantities = array(
      // Test a couple of whole numbers.
      '1',
      '10',
      // Test a couple of mixed numbers.
      '1 1/2',
      '10 1/2',
      // Test the fractions which convert to repeating decimals that are
      // converted by recipe_ingredient_quantity_from_decimal().
      '1/3',
      '2/3',
      '1/6',
      '5/6',
      '1/9',
      '2/9',
      '4/9',
      '5/9',
      '7/9',
      '8/9',
      '1/12',
      '5/12',
      '7/12',
      '11/12',
    );

    foreach ($quantities as $quantity) {
      // Convert the fraction quantity to a decimal.
      $decimal = recipe_ingredient_quantity_from_fraction($quantity);
      // Convert the decimal quantity back to a fraction string.
      $fraction = recipe_ingredient_quantity_from_decimal($decimal);
      // Replace the '&frasl;' in the fraction string with '/'.
      $fraction = str_replace('&frasl;', '/', $fraction);
      // Verify the fraction result is the same as the original quantity.
      $this->assertEqual($quantity, $fraction);
    }
  }

}
