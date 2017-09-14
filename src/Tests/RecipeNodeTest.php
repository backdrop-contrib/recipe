<?php

namespace Drupal\recipe\Tests;

/**
 * Tests the functionality of the Recipe content type and Recipe blocks.
 */
class RecipeNodeTest extends RecipeWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe content type',
      'description' => 'Ensure that the recipe content type functions properly.',
      'group' => 'Recipe',
    );
  }

  /**
   * Creates a recipe node using the node form and test the module settings.
   */
  public function testRecipeContent() {
    // Generate values for our test node.
    $title = $this->randomName(16);
    $description = '<em>' . $this->randomName(255) . '</em>';
    $yield_unit = $this->randomName(10);
    $yield = 5;
    $source = '<a href="http://www.example.com">' . $this->randomName(16) . '</a>';
    $notes = '<em>' . $this->randomName(255) . '</em>';
    $instructions = '<em>' . $this->randomname(255) . '</em>';
    $preptime = 60;
    $cooktime = 135;

    // Ingredient with quantity == 1 and unit tablespoon with note.
    $ing_0_quantity = 1;
    $ing_0_unit = 'tablespoon';
    $ing_0_name = $this->randomName(16);
    $ing_0_note = $this->randomName(16);

    // Ingredient with quantity > 1 and unit tablespoon with note.
    $ing_1_quantity = 2;
    $ing_1_unit = 'tablespoon';
    $ing_1_name = $this->randomName(16);
    $ing_1_note = $this->randomName(16);

    // Ingredient with quantity == 0 and unit tablespoon with note.
    $ing_2_quantity = 0;
    $ing_2_unit = 'tablespoon';
    $ing_2_name = $this->randomName(16);
    $ing_2_note = $this->randomName(16);

    // Ingredient without note.
    $ing_3_quantity = 1;
    $ing_3_unit = 'tablespoon';
    $ing_3_name = $this->randomName(16);
    $ing_3_note = '';

    // Ingredient with unit that has no abbreviation.
    $ing_4_quantity = 10;
    $ing_4_unit = 'unit';
    $ing_4_name = $this->randomName(16);
    $ing_4_note = $this->randomName(16);

    // Ingredient with fractional quantity and unit tablespoon.
    $ing_5_quantity = '1/4';
    $ing_5_unit = 'tablespoon';
    $ing_5_name = $this->randomName(16);
    $ing_5_note = '';

    // Ingredient with mixed fractional quantity and unit tablespoon.
    $ing_6_quantity = '2 2/3';
    $ing_6_unit = 'tablespoon';
    $ing_6_name = $this->randomName(16);
    $ing_6_note = '';

    $edit = array(
      'title' => $title,
      'recipe_description[' . LANGUAGE_NONE . '][0][value]' => $description,
      'recipe_yield_unit' => $yield_unit,
      'recipe_yield' => $yield,
      'recipe_source[' . LANGUAGE_NONE . '][0][value]' => $source,
      'recipe_notes[' . LANGUAGE_NONE . '][0][value]' => $notes,
      'recipe_instructions[' . LANGUAGE_NONE . '][0][value]' => $instructions,
      'recipe_prep_time[' . LANGUAGE_NONE . '][0][value]' => $preptime,
      'recipe_cook_time[' . LANGUAGE_NONE . '][0][value]' => $cooktime,
      'recipe_ingredient[' . LANGUAGE_NONE . '][0][quantity]' => $ing_0_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][0][unit_key]' => $ing_0_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][0][name]' => $ing_0_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][0][note]' => $ing_0_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][1][quantity]' => $ing_1_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][1][unit_key]' => $ing_1_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][1][name]' => $ing_1_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][1][note]' => $ing_1_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][2][quantity]' => $ing_2_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][2][unit_key]' => $ing_2_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][2][name]' => $ing_2_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][2][note]' => $ing_2_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][3][quantity]' => $ing_3_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][3][unit_key]' => $ing_3_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][3][name]' => $ing_3_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][3][note]' => $ing_3_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][4][quantity]' => $ing_4_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][4][unit_key]' => $ing_4_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][4][name]' => $ing_4_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][4][note]' => $ing_4_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][5][quantity]' => $ing_5_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][5][unit_key]' => $ing_5_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][5][name]' => $ing_5_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][5][note]' => $ing_5_note,
      'recipe_ingredient[' . LANGUAGE_NONE . '][6][quantity]' => $ing_6_quantity,
      'recipe_ingredient[' . LANGUAGE_NONE . '][6][unit_key]' => $ing_6_unit,
      'recipe_ingredient[' . LANGUAGE_NONE . '][6][name]' => $ing_6_name,
      'recipe_ingredient[' . LANGUAGE_NONE . '][6][note]' => $ing_6_note,
    );

    $this->drupalGet('node/add/recipe');
    // Add six recipe_ingredient widgets.
    $this->drupalPost(NULL, array(), t('Add another item'));
    $this->drupalPost(NULL, array(), t('Add another item'));
    $this->drupalPost(NULL, array(), t('Add another item'));
    $this->drupalPost(NULL, array(), t('Add another item'));
    $this->drupalPost(NULL, array(), t('Add another item'));
    $this->drupalPost(NULL, array(), t('Add another item'));
    // Post the values to the node form.
    $this->drupalPost(NULL, $edit, t('Save'));
    $this->assertText(t('Recipe @title has been created.', array('@title' => $title)));

    // Check the page for the recipe content.
    $this->assertRaw($description, 'Found the recipe description.');
    $this->assertFieldById('edit-custom-yield', $yield, 'Found the recipe yield in the custom yield form.');
    $this->assertText($yield_unit, 'Found the recipe yield unit.');
    $this->assertRaw($source, 'Found the recipe source.');
    $this->assertRaw($notes, 'Found the recipe notes.');
    $this->assertRaw($instructions, 'Found the recipe instructions');
    $this->assertText('1 hour', 'Found the recipe prep time.');
    $this->assertText('2 hours, 15 minutes', 'Found the recipe cook time.');
    $this->assertText('3 hours, 15 minutes', 'Found the recipe total time.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['abbreviation'])), 'Found ingredient 0 quantity and abbreviation.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0 name and note.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['abbreviation'])), 'Found ingredient 1 quantity and abbreviation.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1 name and note.');

    $this->assertNoText(t('@quantity @unit', array('@quantity' => $ing_2_quantity, '@unit' => $this->unitList[$ing_2_unit]['abbreviation'])), 'Did not find ingredient 2 quantity == 0.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2 name and note.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['abbreviation'])), 'Found ingredient 3 quantity and abbreviation.');
    $this->assertNoText(format_string('@name (@note)', array('@name' => $ing_3_name, '@note' => $ing_3_note)), 'Did not find ingredient 3 name with blank note field, "()".');

    $this->assertRaw(format_string('<span class="quantity-unit" property="schema:amount"> @quantity </span>', array('@quantity' => $ing_4_quantity)), 'Found ingredient 4 quantity with no unit.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4 name and note.');

    $this->assertRaw(str_replace('/', '&frasl;', $ing_5_quantity), 'Found ingredient 5 quantity.');

    $this->assertRaw(str_replace('/', '&frasl;', $ing_6_quantity), 'Found ingredient 6 quantity.');

    // Check the page HTML for the recipe RDF properties.
    $properties = array(
      'schema:Recipe',
      'schema:name',
      'schema:instructions',
      'schema:summary',
      'schema:prepTime',
      'schema:cookTime',
      'schema:totalTime',
      // @todo 'schema:yield' is defined in recipe_rdf_mapping(), but is not
      // currently implemented in any theme function.
      //'schema:yield',
    );
    foreach ($properties as $property) {
      $this->assertRaw($property, format_string('Found the RDF property "@property" in the recipe node HTML.', array('@property' => $property)));
    }

    // Check the page HTML for the ISO 8601 recipe durations.
    $durations = array(
      'prep_time' => 'PT1H',
      'cook_time' => 'PT2H15M',
      'total_time' => 'PT3H15M',
    );
    foreach ($durations as $duration) {
      $this->assertRaw($duration, format_string('Found the ISO 8601 duration "@duration" in the recipe node HTML.', array('@duration' => $duration)));
    }

    // Change the ingredient field settings.
    $instance = field_read_instance('node', 'recipe_ingredient', 'recipe');
    // Enable full unit name display.
    $instance['display']['default']['settings']['unit_abbreviation'] = 1;
    field_update_instance($instance);

    // Change the Recipe module settings.
    $summary_title = $this->randomName(16);
    $edit = array(
      // Hide the recipe summary.
      // @todo The recipe summary location setting currently does nothing.
      //'recipe_summary_location' => 2,
      // Change the Summary block title.
      'recipe_summary_title' => $summary_title,
    );

    // Post the values to the settings form.
    $this->drupalPost('admin/config/content/recipe', $edit, t('Save configuration'));

    // Check the recipe node display again.
    $this->drupalGet('node/1');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'])), 'Found ingredient 0 quantity and singular unit name.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'])), 'Found ingredient 1 quantity and plural unit name.');

    //$this->assertNoText(t('Summary'), 'Did not find the recipe summary.');

    // Enable the Newest Recipes and Recipe Summary blocks.
    // Check for it and the node link.
    $edit = array(
      "blocks[recipe_recent][region]" => 'sidebar_first',
      "blocks[recipe_summary][region]" => 'sidebar_first',
    );
    $this->drupalPost('admin/structure/block', $edit, t('Save blocks'));
    $this->assertText(t('Newest recipes'), 'Found the Newest recipes block.');
    $this->assertLink($title, 0);
    // Make sure the Summary block doesn't appear on a non-recipe-node page.
    $this->assertNoText($summary_title, 'Did not find the altered Summary block title.');

    // Check for the Summary block on the recipe node page.
    $this->drupalGet('node/1');
    $this->assertText($summary_title, 'Found the altered Summary block title.');

    // Test ingredient autocomplete for the first ingredient.
    $input = substr($ing_0_name, 0, 3);
    $this->drupalGet('recipe/ingredient/autocomplete/' . $input);
    $this->assertRaw('{"' . $ing_0_name . '":"' . $ing_0_name . '"}', format_string('Autocomplete returns ingredient %ingredient_name after typing the first 3 letters.', array('%ingredient_name' => $ing_0_name)));

    // Test the export formats.
    // Check that the export format links are displayed on the recipe node page.
    $this->drupalGet('node/1');
    $this->assertLink('Print View', 0);
    $this->assertLink('MasterCook4', 0);
    $this->assertLink('Plain Text', 0);
    $this->assertLink('recipeML', 0);

    // Check for the recipe data on the HTML export page.
    $this->drupalGet('recipe/export/recipeprint/1/' . $yield);
    $this->assertRaw($description, 'Found the recipe description.');
    // The HTML format does not output the yield.
    // The HTML format does not output the yield unit.
    $this->assertRaw($source, 'Found the recipe source.');
    $this->assertRaw($notes, 'Found the recipe notes.');
    $this->assertRaw($instructions, 'Found the recipe instructions');
    $this->assertRaw('1 hour', 'Found the recipe prep time.');
    $this->assertRaw('2 hours, 15 minutes', 'Found the recipe cook time.');
    $this->assertRaw('3 hours, 15 minutes', 'Found the recipe total time.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'])), 'Found ingredient 0 quantity and unit name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0 name and note.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'])), 'Found ingredient 1 quantity and unit plural name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1 name and note.');

    $this->assertRaw(format_string('!quantity @unit', array('!quantity' => '&nbsp;', '@unit' => $this->unitList[$ing_2_unit]['name'])), 'Found ingredient 2 unit name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2 name and note.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'])), 'Found ingredient 3 quantity and unit name.');
    $this->assertRaw($ing_3_name, 'Found ingredient 3 name.');

    $this->assertRaw(format_string('<span class="quantity-unit" property="schema:amount"> @quantity </span>', array('@quantity' => $ing_4_quantity)), 'Found ingredient 4 quantity and with no unit.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4 name and note.');

    // Check for the recipe data on the MasterCook4 export page.
    $this->drupalGet('recipe/export/mastercook4/1/' . $yield);
    $this->assertRaw(format_string('DESCRIPTION : @description', array('@description' => filter_xss($description, array()))), 'Found the recipe description.');
    $this->assertRaw(format_string('Serving Size  : @yield', array('@yield' => $yield)), 'Found the recipe yield.');
    // The MasterCook4 format does not output the yield unit.
    $this->assertRaw(format_string('Recipe By     : @source', array('@source' => filter_xss($source, array()))), 'Found the recipe source.');
    $this->assertRaw(filter_xss($notes, array()), 'Found the recipe notes.');
    $this->assertRaw(filter_xss($instructions, array()), 'Found the recipe instructions');
    $this->assertRaw('Preparation Time : 1:00', 'Found the recipe prep time.');
    // The MasterCook4 format does not output the cook time.
    // The MasterCook4 format does not output the total time.
    $this->assertRaw(format_string('@quantity  @unit    @name -- @note', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'], '@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0.');
    $this->assertRaw(format_string('@quantity  @unit   @name -- @note', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'], '@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1.');
    $this->assertRaw(format_string('   @unit    @name -- @note', array('@unit' => $this->unitList[$ing_2_unit]['name'], '@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2.');
    $this->assertRaw(format_string('@quantity  @unit    @name', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'], '@name' => $ing_3_name)), 'Found ingredient 3.');
    $this->assertRaw(format_string('@quantity                @name -- @note', array('@quantity' => $ing_4_quantity, '@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4.');

    // Check for the recipe data on the plain text export page.
    $this->drupalGet('recipe/export/plaintext/1/' . $yield);
    $this->assertRaw(filter_xss($description, array()), 'Found the recipe description.');
    // The plain text format does not output the yield.
    // The plain text format does not output the yield unit.
    // The plain text format does not output the source.
    $this->assertRaw(filter_xss($notes, array()), 'Found the recipe notes.');
    $this->assertRaw(filter_xss($instructions, array()), 'Found the recipe instructions');
    // The plain text format does not output the prep time.
    // The plain text format does not output the cook time.
    // The plain text format does not output the total time.
    $this->assertRaw(format_string('@quantity @unit  @name (@note)', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'], '@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0.');
    $this->assertRaw(format_string('@quantity @unit @name (@note)', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'], '@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1.');
    $this->assertRaw(format_string('   @unit  @name (@note)', array('@unit' => $this->unitList[$ing_2_unit]['name'], '@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2.');
    $this->assertRaw(format_string('@quantity @unit  @name', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'], '@name' => $ing_3_name)), 'Found ingredient 3.');
    $this->assertRaw(format_string('@quantity             @name (@note)', array('@quantity' => $ing_4_quantity, '@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4.');

    // Check for the recipe data on the recipeML export page.
    $this->drupalGet('recipe/export/recipeml/1/' . $yield);
    $this->assertRaw(format_string('<description>@description</description>', array('@description' => filter_xss($description, array()))), 'Found the recipe description.');
    $this->assertRaw(format_string('<yield><qty>@yield</qty><unit>@yield_unit</unit></yield>', array('@yield' => $yield, '@yield_unit' => $yield_unit)), 'Found the recipe yield and yield unit.');
    $this->assertRaw(format_string('<source>@source</source>', array('@source' => filter_xss($source, array()))), 'Found the recipe source.');
    $this->assertRaw(format_string('<note>@notes</note>', array('@notes' => filter_xss($notes, array()))), 'Found the recipe notes.');
    $this->assertRaw(format_string('<directions>@instructions</directions>', array('@instructions' => filter_xss($instructions, array()))), 'Found the recipe instructions');
    $this->assertRaw(format_string('<preptime type="cooking"><time><qty>@preptime</qty><timeunit>minutes</timeunit></time></preptime>', array('@preptime' => $preptime)), 'Found the recipe prep time.');
    // The recipeML format does not output the cook time.
    // The recipeML format does not output the total time.
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'], '@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0.');
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'], '@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1.');
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $ing_2_quantity, '@unit' => $this->unitList[$ing_2_unit]['name'], '@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2.');
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item></ing>', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'], '@name' => $ing_3_name, '@note' => $ing_0_note)), 'Found ingredient 3.');
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit> </unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $ing_4_quantity, '@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4.');

    // Check for the description in the teaser view at /node.
    $this->drupalGet('node');
    $this->assertRaw($description, 'Found the recipe description.');

    // Check for fractional quantities when editing the node.
    $this->drupalGet('node/1/edit');
    $this->assertFieldById('edit-recipe-ingredient-' . LANGUAGE_NONE . '-5-quantity', $ing_5_quantity, 'Found fractional quantity in the 5th ingredient field on the node edit form.');
    $this->assertFieldById('edit-recipe-ingredient-' . LANGUAGE_NONE . '-6-quantity', $ing_6_quantity, 'Found fractional quantity in the 6th ingredient field on the node edit form.');

    // Check for the altered unit list when editing the node.
    $this->assertFieldByXPath('//option[@value="test_unit"]', NULL, 'Found the altered unit.');
  }

  /**
   * Tests exporting recipes with the multiple export page.
   */
  public function testRecipeMultipleExport() {
    // Create two recipe nodes.
    $node_title_1 = $this->randomName(16);
    $source_1 = $this->randomName(16);
    $yield_1 = 10;
    $yield_unit_1 = $this->randomName(10);
    $description_1 = $this->randomName(32);
    $instructions_1 = $this->randomName(32);
    $notes_1 = $this->randomName(32);
    $preptime_1 = 30;
    $cooktime_1 = 90;
    $quantity_1 = 2;
    $unit_key_1 = 'cup';
    $ingredient_name_1 = $this->randomName(16);
    $ingredient_note_1 = $this->randomName(16);
    $edit = array(
      'type' => 'recipe',
      'title' => $node_title_1,
      'recipe_source' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $source_1,
          ),
        ),
      ),
      'recipe_yield' => $yield_1,
      'recipe_yield_unit' => $yield_unit_1,
      'recipe_description' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $description_1,
          ),
        ),
      ),
      'recipe_instructions' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $instructions_1,
          ),
        ),
      ),
      'recipe_notes' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $notes_1,
          ),
        ),
      ),
      'recipe_prep_time' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $preptime_1,
          ),
        ),
      ),
      'recipe_cook_time' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $cooktime_1,
          ),
        ),
      ),
      'recipe_ingredient' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'iid' => recipe_ingredient_id_from_name($ingredient_name_1),
            'quantity' => $quantity_1,
            'unit_key' => $unit_key_1,
            'note' => $ingredient_note_1,
          ),
        ),
      ),
    );
    $this->drupalCreateNode($edit);

    $node_title_2 = $this->randomName(16);
    $source_2 = $this->randomName(16);
    $yield_2 = 10;
    $yield_unit_2 = $this->randomName(10);
    $description_2 = $this->randomName(32);
    $instructions_2 = $this->randomName(32);
    $notes_2 = $this->randomName(32);
    $preptime_2 = 15;
    $cooktime_2 = 45;
    $quantity_2 = 2;
    $unit_key_2 = 'cup';
    $ingredient_name_2 = $this->randomName(16);
    $ingredient_note_2 = $this->randomName(16);
    $edit = array(
      'type' => 'recipe',
      'title' => $node_title_2,
      'recipe_source' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $source_2,
          ),
        ),
      ),
      'recipe_yield' => $yield_2,
      'recipe_yield_unit' => $yield_unit_2,
      'recipe_description' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $description_2,
          ),
        ),
      ),
      'recipe_instructions' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $instructions_2,
          ),
        ),
      ),
      'recipe_notes' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $notes_2,
          ),
        ),
      ),
      'recipe_prep_time' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $preptime_2,
          ),
        ),
      ),
      'recipe_cook_time' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'value' => $cooktime_2,
          ),
        ),
      ),
      'recipe_ingredient' => array(
        LANGUAGE_NONE => array(
          0 => array(
            'iid' => recipe_ingredient_id_from_name($ingredient_name_2),
            'quantity' => $quantity_2,
            'unit_key' => $unit_key_2,
            'note' => $ingredient_note_2,
          ),
        ),
      ),
    );
    $this->drupalCreateNode($edit);

    // Check for links to all the export formats on the bulk export page.
    $this->drupalGet('admin/structure/recipe');
    $this->assertLink('MasterCook4');
    $this->assertLink('Plain Text');
    $this->assertLink('recipeML');

    // Check for the recipe data on the MasterCook4 bulk export page.
    $this->drupalGet('admin/structure/recipe/export_multi/mastercook4');
    $this->assertRaw($node_title_1);
    // The MasterCook4 format does not output the description.
    $this->assertRaw(format_string('Serving Size  : @yield', array('@yield' => $yield_1)), 'Found the recipe yield.');
    // The MasterCook4 format does not output the yield unit.
    $this->assertRaw(format_string('Recipe By     : @source', array('@source' => $source_1)), 'Found the recipe source.');
    $this->assertRaw($notes_1, 'Found the recipe notes.');
    $this->assertRaw($instructions_1, 'Found the recipe instructions');
    $this->assertRaw('Preparation Time : 0:30', 'Found the recipe prep time.');
    // The MasterCook4 format does not output the cook time.
    // The MasterCook4 format does not output the total time.
    $this->assertRaw(format_string('@quantity  @unit             @name -- @note', array('@quantity' => $quantity_1, '@unit' => $this->unitList[$unit_key_1]['abbreviation'], '@name' => $ingredient_name_1, '@note' => $ingredient_note_1)), 'Found ingredient 0.');

    $this->assertRaw($node_title_2);
    // The MasterCook4 format does not output the description.
    $this->assertRaw(format_string('Serving Size  : @yield', array('@yield' => $yield_2)), 'Found the recipe yield.');
    // The MasterCook4 format does not output the yield unit.
    $this->assertRaw(format_string('Recipe By     : @source', array('@source' => $source_2)), 'Found the recipe source.');
    $this->assertRaw($notes_2, 'Found the recipe notes.');
    $this->assertRaw($instructions_2, 'Found the recipe instructions');
    $this->assertRaw('Preparation Time : 0:15', 'Found the recipe prep time.');
    // The MasterCook4 format does not output the cook time.
    // The MasterCook4 format does not output the total time.
    $this->assertRaw(format_string('@quantity  @unit             @name -- @note', array('@quantity' => $quantity_2, '@unit' => $this->unitList[$unit_key_2]['abbreviation'], '@name' => $ingredient_name_2, '@note' => $ingredient_note_2)), 'Found ingredient 0.');

    // Check for the recipe data on the plain text export page.
    $this->drupalGet('admin/structure/recipe/export_multi/plaintext');
    $this->assertRaw($node_title_1);
    $this->assertRaw($description_1, 'Found the recipe description.');
    // The plain text format does not output the yield.
    // The plain text format does not output the yield unit.
    // The plain text format does not output the source.
    $this->assertRaw($notes_1, 'Found the recipe notes.');
    $this->assertRaw($instructions_1, 'Found the recipe instructions');
    // The plain text format does not output the prep time.
    // The plain text format does not output the cook time.
    // The plain text format does not output the total time.
    $this->assertRaw(format_string('@quantity @unit @name (@note)', array('@quantity' => $quantity_1, '@unit' => $this->unitList[$unit_key_1]['abbreviation'], '@name' => $ingredient_name_1, '@note' => $ingredient_note_1)), 'Found ingredient 0.');

    $this->assertRaw($node_title_2);
    $this->assertRaw($description_2, 'Found the recipe description.');
    // The plain text format does not output the yield.
    // The plain text format does not output the yield unit.
    // The plain text format does not output the source.
    $this->assertRaw($notes_2, 'Found the recipe notes.');
    $this->assertRaw($instructions_2, 'Found the recipe instructions');
    // The plain text format does not output the prep time.
    // The plain text format does not output the cook time.
    // The plain text format does not output the total time.
    $this->assertRaw(format_string('@quantity @unit @name (@note)', array('@quantity' => $quantity_2, '@unit' => $this->unitList[$unit_key_2]['abbreviation'], '@name' => $ingredient_name_2, '@note' => $ingredient_note_2)), 'Found ingredient 0.');

    // Check for the recipe data on the recipeML export page.
    $this->drupalGet('admin/structure/recipe/export_multi/recipeml');
    $this->assertRaw($node_title_1);
    $this->assertRaw(format_string('<description>@description</description>', array('@description' => $description_1)), 'Found the recipe description.');
    $this->assertRaw(format_string('<yield><qty>@yield</qty><unit>@yield_unit</unit></yield>', array('@yield' => $yield_1, '@yield_unit' => $yield_unit_1)), 'Found the recipe yield and yield unit.');
    $this->assertRaw(format_string('<source>@source</source>', array('@source' => $source_1)), 'Found the recipe source.');
    $this->assertRaw(format_string('<note>@notes</note>', array('@notes' => $notes_1)), 'Found the recipe notes.');
    $this->assertRaw(format_string('<directions>@instructions</directions>', array('@instructions' => $instructions_1)), 'Found the recipe instructions');
    $this->assertRaw(format_string('<preptime type="cooking"><time><qty>@preptime</qty><timeunit>minutes</timeunit></time></preptime>', array('@preptime' => $preptime_1)), 'Found the recipe prep time.');
    // The recipeML format does not output the cook time.
    // The recipeML format does not output the total time.
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $quantity_1, '@unit' => $this->unitList[$unit_key_1]['abbreviation'], '@name' => $ingredient_name_1, '@note' => $ingredient_note_1)), 'Found ingredient 0.');

    $this->assertRaw($node_title_2);
    $this->assertRaw(format_string('<description>@description</description>', array('@description' => $description_2)), 'Found the recipe description.');
    $this->assertRaw(format_string('<yield><qty>@yield</qty><unit>@yield_unit</unit></yield>', array('@yield' => $yield_2, '@yield_unit' => $yield_unit_2)), 'Found the recipe yield and yield unit.');
    $this->assertRaw(format_string('<source>@source</source>', array('@source' => $source_2)), 'Found the recipe source.');
    $this->assertRaw(format_string('<note>@notes</note>', array('@notes' => $notes_2)), 'Found the recipe notes.');
    $this->assertRaw(format_string('<directions>@instructions</directions>', array('@instructions' => $instructions_2)), 'Found the recipe instructions');
    $this->assertRaw(format_string('<preptime type="cooking"><time><qty>@preptime</qty><timeunit>minutes</timeunit></time></preptime>', array('@preptime' => $preptime_2)), 'Found the recipe prep time.');
    // The recipeML format does not output the cook time.
    // The recipeML format does not output the total time.
    $this->assertRaw(format_string('<ing><amt><qty>@quantity</qty><unit>@unit</unit></amt><item>@name</item><prep>@note</prep></ing>', array('@quantity' => $quantity_2, '@unit' => $this->unitList[$unit_key_2]['abbreviation'], '@name' => $ingredient_name_2, '@note' => $ingredient_note_2)), 'Found ingredient 0.');
  }

}
