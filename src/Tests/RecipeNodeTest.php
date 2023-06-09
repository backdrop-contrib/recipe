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
    $description = $this->randomName(255);
    $yield_unit = $this->randomName(10);
    $yield = 5;
    $source = $this->randomName(16);
    $notes = $this->randomName(255);
    $instructions = $this->randomname(255);
    $preptime = 60;
    $cooktime = 15;

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

    $edit = array(
      'title' => $title,
      'recipe_description[value]' => $description,
      'recipe_yield_unit' => $yield_unit,
      'recipe_yield' => $yield,
      'recipe_source' => $source,
      'recipe_notes[value]' => $notes,
      'recipe_instructions[value]' => $instructions,
      'recipe_preptime' => $preptime,
      'recipe_cooktime' => $cooktime,
      'recipe_ingredients[ing][0][quantity]' => $ing_0_quantity,
      'recipe_ingredients[ing][0][unit_key]' => $ing_0_unit,
      'recipe_ingredients[ing][0][name]' => $ing_0_name,
      'recipe_ingredients[ing][0][note]' => $ing_0_note,
      'recipe_ingredients[ing][1][quantity]' => $ing_1_quantity,
      'recipe_ingredients[ing][1][unit_key]' => $ing_1_unit,
      'recipe_ingredients[ing][1][name]' => $ing_1_name,
      'recipe_ingredients[ing][1][note]' => $ing_1_note,
      'recipe_ingredients[ing][2][quantity]' => $ing_2_quantity,
      'recipe_ingredients[ing][2][unit_key]' => $ing_2_unit,
      'recipe_ingredients[ing][2][name]' => $ing_2_name,
      'recipe_ingredients[ing][2][note]' => $ing_2_note,
      'recipe_ingredients[ing][3][quantity]' => $ing_3_quantity,
      'recipe_ingredients[ing][3][unit_key]' => $ing_3_unit,
      'recipe_ingredients[ing][3][name]' => $ing_3_name,
      'recipe_ingredients[ing][3][note]' => $ing_3_note,
      'recipe_ingredients[ing][4][quantity]' => $ing_4_quantity,
      'recipe_ingredients[ing][4][unit_key]' => $ing_4_unit,
      'recipe_ingredients[ing][4][name]' => $ing_4_name,
      'recipe_ingredients[ing][4][note]' => $ing_4_note,
    );

    // Post the values to the node form.
    $this->drupalPost('node/add/recipe', $edit, t('Save'));
    $this->assertText(t('Recipe @title has been created.', array('@title' => $title)));

    // Check the page for the recipe content.
    $this->assertText($description, 'Found the recipe description.');
    $this->assertFieldById('edit-custom-yield', $yield, 'Found the recipe yield in the custom yield form.');
    $this->assertText($yield_unit, 'Found the recipe yield unit.');
    $this->assertText($source, 'Found the recipe source.');
    $this->assertText($notes, 'Found the recipe notes.');
    $this->assertText($instructions, 'Found the recipe instructions');
    $this->assertText($this->formatTime($preptime), 'Found the recipe prep time.');
    $this->assertText($this->formatTime($cooktime), 'Found the recipe cook time.');
    $this->assertText($this->formatTime($preptime + $cooktime), 'Found the recipe total time.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['abbreviation'])), 'Found ingredient 0 quantity and abbreviation.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0 name and note.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['abbreviation'])), 'Found ingredient 1 quantity and abbreviation.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1 name and note.');

    $this->assertNoText(t('@quantity @unit', array('@quantity' => $ing_2_quantity, '@unit' => $this->unitList[$ing_2_unit]['abbreviation'])), 'Did not find ingredient 2 quantity == 0.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2 name and note.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['abbreviation'])), 'Found ingredient 3 quantity and abbreviation.');
    $this->assertNoText(format_string('@name (@note)', array('@name' => $ing_3_name, '@note' => $ing_3_note)), 'Did not find ingredient 3 name with blank note field, "()".');

    $this->assertRaw(format_string('<div class="quantity-unit" property="schema:amount"> @quantity </div>', array('@quantity' => $ing_4_quantity)), 'Found ingredient 4 quantity with no unit.');
    $this->assertText(format_string('@name (@note)', array('@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4 name and note.');

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
      // 'schema:yield',
    );
    foreach ($properties as $property) {
      $this->assertRaw($property, format_string('Found the RDF property "@property" in the recipe node HTML.', array('@property' => $property)));
    }

    // Change the Recipe module settings.
    $summary_title = $this->randomName(16);
    $edit = array(
      // Enable full unit name display.
      'recipe_unit_display' => 1,
      // Enable lowercase normalization of ingredient names.
      // @todo The ingredient name normalization setting currently does nothing.
      //'recipe_ingredient_name_normalize' => 1,
      // Hide the recipe summary.
      // @todo The recipe summary location setting currently does nothing.
      //'recipe_summary_location' => 2,
      // Change the Summary block title.
      'recipe_summary_title' => $summary_title,
    );

    // Post the values to the settings form.
    $this->drupalPost('admin/config/system/recipe', $edit, t('Save configuration'));

    // Check the recipe node display again.
    $this->drupalGet('node/1');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'])), 'Found ingredient 0 quantity and singular unit name.');

    $this->assertText(t('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'])), 'Found ingredient 1 quantity and plural unit name.');

    //$this->assertText(strtolower($ing_0_name), 'Found normalized ingredient 0 name.');
    //$this->assertText(strtolower($ing_1_name), 'Found normalized ingredient 1 name.');
    //$this->assertText(strtolower($ing_2_name), 'Found normalized ingredient 2 name.');
    //$this->assertText(strtolower($ing_3_name), 'Found normalized ingredient 3 name.');
    //$this->assertText(strtolower($ing_4_name), 'Found normalized ingredient 4 name.');

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
    $this->assertRaw(t('Prep time: @time', array('@time' => $this->formatHtmlTime($preptime))), 'Found the recipe prep time.');
    $this->assertRaw(t('Cooking time: @time', array('@time' => $this->formatHtmlTime($cooktime))), 'Found the recipe cook time.');
    $this->assertRaw(t('Total time: !time', array('!time' => $this->formatHtmlTime($preptime + $cooktime))), 'Found the recipe total time.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'])), 'Found ingredient 0 quantity and unit name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0 name and note.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'])), 'Found ingredient 1 quantity and unit plural name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1 name and note.');

    $this->assertRaw(format_string('!quantity @unit', array('!quantity' => '&nbsp;', '@unit' => $this->unitList[$ing_2_unit]['name'])), 'Found ingredient 2 unit name.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2 name and note.');

    $this->assertRaw(format_string('@quantity @unit', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'])), 'Found ingredient 3 quantity and unit name.');
    $this->assertRaw($ing_3_name, 'Found ingredient 3 name.');

    $this->assertRaw(format_string('<div class="quantity-unit" property="schema:amount"> @quantity </div>', array('@quantity' => $ing_4_quantity)), 'Found ingredient 4 quantity and with no unit.');
    $this->assertRaw(format_string('@name (@note)', array('@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4 name and note.');

    // Check for the recipe data on the MasterCook4 export page.
    $this->drupalGet('recipe/export/mastercook4/1/' . $yield);
    // The MasterCook4 format does not output the description.
    $this->assertRaw(format_string('Serving Size  : @yield', array('@yield' => $yield)), 'Found the recipe yield.');
    // The MasterCook4 format does not output the yield unit.
    $this->assertRaw(format_string('Recipe By     : @source', array('@source' => $source)), 'Found the recipe source.');
    $this->assertRaw($notes, 'Found the recipe notes.');
    $this->assertRaw($instructions, 'Found the recipe instructions');
    $hours = (int) ($preptime / 60);
    $minutes = $preptime % 60;
    $mastercook_time = $hours . ':' . $minutes;
    $this->assertRaw(format_string('Preparation Time :@time', array('@time' => $mastercook_time)), 'Found the recipe prep time.');
    // The MasterCook4 format does not output the cook time.
    // The MasterCook4 format does not output the total time.
    $this->assertRaw(format_string('@quantity  @unit    @name -- @note', array('@quantity' => $ing_0_quantity, '@unit' => $this->unitList[$ing_0_unit]['name'], '@name' => $ing_0_name, '@note' => $ing_0_note)), 'Found ingredient 0.');
    $this->assertRaw(format_string('@quantity  @unit   @name -- @note', array('@quantity' => $ing_1_quantity, '@unit' => $this->unitList[$ing_1_unit]['plural'], '@name' => $ing_1_name, '@note' => $ing_1_note)), 'Found ingredient 1.');
    $this->assertRaw(format_string('   @unit    @name -- @note', array('@unit' => $this->unitList[$ing_2_unit]['name'], '@name' => $ing_2_name, '@note' => $ing_2_note)), 'Found ingredient 2.');
    $this->assertRaw(format_string('@quantity  @unit    @name', array('@quantity' => $ing_3_quantity, '@unit' => $this->unitList[$ing_3_unit]['name'], '@name' => $ing_3_name)), 'Found ingredient 3.');
    $this->assertRaw(format_string('@quantity                @name -- @note', array('@quantity' => $ing_4_quantity, '@name' => $ing_4_name, '@note' => $ing_4_note)), 'Found ingredient 4.');

    // Check for the recipe data on the plain text export page.
    $this->drupalGet('recipe/export/plaintext/1/' . $yield);
    $this->assertRaw($description, 'Found the recipe description.');
    // The plain text format does not output the yield.
    // The plain text format does not output the yield unit.
    // The plain text format does not output the source.
    $this->assertRaw($notes, 'Found the recipe notes.');
    $this->assertRaw($instructions, 'Found the recipe instructions');
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
    $this->assertRaw(format_string('<description>@description</description>', array('@description' => $description)), 'Found the recipe description.');
    $this->assertRaw(format_string('<yield><qty>@yield</qty><unit>@yield_unit</unit></yield>', array('@yield' => $yield, '@yield_unit' => $yield_unit)), 'Found the recipe yield and yield unit.');
    $this->assertRaw(format_string('<source>@source</source>', array('@source' => $source)), 'Found the recipe source.');
    $this->assertRaw(format_string('<note>@notes</note>', array('@notes' => $notes)), 'Found the recipe notes.');
    $this->assertRaw(format_string('<directions>@instructions</directions>', array('@instructions' => $instructions)), 'Found the recipe instructions');
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
    $this->assertText($description, 'Found the recipe description.');

    // Check for the altered unit list when editing the node.
    $this->drupalGet('node/1/edit');
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
      'recipe_source' => $source_1,
      'recipe_yield' => $yield_1,
      'recipe_yield_unit' => $yield_unit_1,
      'recipe_description' => array(
        'value' => $description_1,
      ),
      'recipe_instructions' => array(
        'value' => $instructions_1,
      ),
      'recipe_notes' => array(
        'value' => $notes_1,
      ),
      'recipe_preptime' => $preptime_1,
      'recipe_cooktime' => $cooktime_1,
      'recipe_ingredients' => array(
        'ing' => array(
          0 => array(
            'quantity' => $quantity_1,
            'unit_key' => $unit_key_1,
            'name' => $ingredient_name_1,
            'note' => $ingredient_note_1,
            'weight' => 0,
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
      'recipe_source' => $source_2,
      'recipe_yield' => $yield_2,
      'recipe_yield_unit' => $yield_unit_2,
      'recipe_description' => array(
        'value' => $description_2,
      ),
      'recipe_instructions' => array(
        'value' => $instructions_2,
      ),
      'recipe_notes' => array(
        'value' => $notes_2,
      ),
      'recipe_preptime' => $preptime_2,
      'recipe_cooktime' => $cooktime_2,
      'recipe_ingredients' => array(
        'ing' => array(
          0 => array(
            'quantity' => $quantity_2,
            'unit_key' => $unit_key_2,
            'name' => $ingredient_name_2,
            'note' => $ingredient_note_2,
            'weight' => 0,
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
    $hours = (int) ($preptime_1 / 60);
    $minutes = $preptime_1 % 60;
    $mastercook_time = $hours . ':' . $minutes;
    $this->assertRaw(format_string('Preparation Time :@time', array('@time' => $mastercook_time)), 'Found the recipe prep time.' . $mastercook_time);
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
    $hours = (int) ($preptime_2 / 60);
    $minutes = $preptime_2 % 60;
    $mastercook_time = $hours . ':' . $minutes;
    $this->assertRaw(format_string('Preparation Time :@time', array('@time' => $mastercook_time)), 'Found the recipe prep time.' . $mastercook_time);
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

  /**
   * Format recipe times for display.
   *
   * @todo This function and the code its copied from in theme_recipe_summary()
   * need to be replaced with an equivalent function in recipe.module.
   */
  protected function formatTime($time) {
    $_o_minutes = $time;
    $_hours = floor($_o_minutes / 60);
    $_minutes = $_o_minutes - ($_hours * 60);
    $_text = '';
    if ($_hours > 0) {
      $_text = format_plural($_hours, '1 hour', '@count hours');
    }
    if ($_minutes > 0) {
      if (strlen($_text) > 0) {
        $_text .= ', ';
      }
      $_text .= format_plural($_minutes, '1 minute', '@count minutes');
    }
    return $_text;
  }

  /**
   * Format recipe times for display on the HTML export.
   *
   * @todo This horrible time display logic needs to be eliminated.
   */
  protected function formatHtmlTime($time) {
    if ($time < 60) {
      return format_plural($time, '1 minute', '@count minutes');
    }
    elseif ($time % 60 == 0) {
      return format_plural($time / 60, '1 hour', '@count hours');
    }
    else {
      return t('!time hours', array('!time' => recipe_ingredient_quantity_from_decimal($time / 60)));
    }
  }

}
