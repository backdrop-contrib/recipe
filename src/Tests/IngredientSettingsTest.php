<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the functionality of the ingredient field settings.
 */
class IngredientSettingsTest extends RecipeWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe ingredient settings',
      'description' => 'Ensure that the ingredient field settings function properly.',
      'group' => 'Recipe',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // Enable modules required for testing.
    parent::setUp(array('recipe'));

    // Create a new content type for testing.
    $this->drupalCreateContentType(array('type' => 'test_bundle'));

    // Create and log in the admin user with Recipe content permissions.
    $this->adminUser = $this->drupalCreateUser(array('create test_bundle content', 'administer site configuration'));
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests ingredient field settings.
   */
  public function testIngredientFieldSettings() {
    // Create the field.
    $field = array(
      'cardinality' => -1,
      'field_name' => 'ingredient',
      'module' => 'recipe',
      'settings' => array(
        'ingredient_name_normalize' => 1,
      ),
      'type' => 'ingredient_reference',
    );
    field_create_field($field);

    // Create the field instance.
    $instance = array(
      'bundle' => 'test_bundle',
      'display' => array(
        'default' => array(
          'label' => 'above',
          'module' => 'recipe',
          'settings' => array(
            'fraction_format' => '{%d }%d&frasl;%d',
            'unit_abbreviation' => 0,
          ),
          'type' => 'recipe_ingredient_default',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'node',
      'field_name' => 'ingredient',
      'label' => 'Ingredients',
      'widget' => array(
        'active' => 0,
        'module' => 'recipe',
        'settings' => array(
          'default_unit' => 'cup',
        ),
        'type' => 'recipe_ingredient_autocomplete',
        'weight' => 0,
      ),
    );
    field_create_instance($instance);

    $edit = array(
      'title' => $this->randomName(16),
      'ingredient[' . LANGUAGE_NONE . '][0][quantity]' => 4,
      'ingredient[' . LANGUAGE_NONE . '][0][unit_key]' => 'us gallon',
      'ingredient[' . LANGUAGE_NONE . '][0][name]' => 'TeSt InGrEdIeNt',
      'ingredient[' . LANGUAGE_NONE . '][0][note]' => '',
    );

    $this->drupalGet('node/add/test_bundle');
    // Assert that the default element, 'cup', is selected.
    $this->assertOptionSelected('edit-ingredient-und-0-unit-key', 'cup', 'The default unit was selected.');
    // Post the values to the node form.
    $this->drupalPost(NULL, $edit, t('Save'));

    // Assert that the normalized ingredient name can be found on the node page.
    $this->assertText('test ingredient', 'Found the normalized ingredient name.');
  }

}
