<?php

namespace Drupal\recipe\Tests;

/**
 * Tests translating Recipe nodes.
 */
class RecipeTranslationTest extends \DrupalWebTestCase {

  /**
   * A user with administrative privileges.
   *
   * @var \stdClass
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Recipe content translation',
      'description' => 'Ensure that the recipe content translation functions properly.',
      'group' => 'Recipe',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    // Enable modules required for testing. Bypass \TranslationTestCase::setUp()
    // because we don't want the configuration that it sets up.
    parent::setUp(array(
      'locale',
      'recipe',
      'translation',
    ));

    // Create and log in the admin user with Recipe content permissions.
    $this->adminUser = $this->drupalCreateUser(array(
      'administer content types',
      'administer languages',
      'administer site configuration',
      'bypass node access',
      'translate content',
    ));
    $this->drupalLogin($this->adminUser);

    // Add languages.
    $this->addLanguage('en');
    $this->addLanguage('es');

    // Set Recipes to use multilingual support with translation.
    $this->drupalGet('admin/structure/types/manage/recipe');
    $edit = array();
    $edit['language_content_type'] = 2;
    $this->drupalPost('admin/structure/types/manage/recipe', $edit, t('Save content type'));
    $this->assertRaw(t('The content type %type has been updated.', array('%type' => 'Recipe')), 'Recipe content type has been updated.');
  }

  /**
   * Tests translation of recipe data.
   */
  public function testRecipeTranslation() {
    // Create a recipe node.
    $node_title = $this->randomName(16);
    $source = $this->randomName(16);
    $yield = 10;
    $yield_unit = $this->randomName(10);
    $description = $this->randomName(32);
    $instructions = $this->randomName(32);
    $notes = $this->randomName(32);
    $preptime = 30;
    $cooktime = 90;
    $quantity = 2;
    $unit_key = 'cup';
    $ingredient_name = $this->randomName(16);
    $ingredient_note = $this->randomName(16);
    $edit = array(
      'type' => 'recipe',
      'title' => $node_title,
      'recipe_source' => $source,
      'recipe_yield' => $yield,
      'recipe_yield_unit' => $yield_unit,
      'recipe_description' => array(
        'value' => $description,
      ),
      'recipe_instructions' => array(
        'value' => $instructions,
      ),
      'recipe_notes' => array(
        'value' => $notes,
      ),
      'recipe_preptime' => $preptime,
      'recipe_cooktime' => $cooktime,
      'recipe_ingredients' => array(
        'ing' => array(
          0 => array(
            'quantity' => $quantity,
            'unit_key' => $unit_key,
            'name' => $ingredient_name,
            'note' => $ingredient_note,
            'weight' => 0,
          ),
        ),
      ),
      'language' => 'en',
    );
    $this->drupalCreateNode($edit);

    // Verify that the node was created and that the translation link exists.
    $this->drupalGet('node/1');
    $this->assertLink('Translate', 0, 'Found the translate link.');
    $this->clickLink('Translate', 0);
    $this->assertLink('add translation', 0, 'Found the add translation link.');
    $this->clickLink('add translation', 0);

    // Verify that the translation form is populated with the source values.
    $this->assertFieldById('edit-recipe-description-value', $description, 'Found the source description.');
    $this->assertFieldById('edit-recipe-yield-unit', $yield_unit, 'Found the source yield units.');
    $this->assertFieldById('edit-recipe-yield', $yield, 'Found the source yield.');
    $this->assertFieldById('edit-recipe-ingredients-ing-0-quantity', $quantity, 'Found the source ingredient quantity.');
    $this->assertFieldById('edit-recipe-ingredients-ing-0-unit-key', $unit_key, 'Found the source ingredient unit key.');
    $this->assertFieldById('edit-recipe-ingredients-ing-0-name', $ingredient_name, 'Found the source ingredient name.');
    $this->assertFieldById('edit-recipe-ingredients-ing-0-note', $ingredient_note, 'Found the source ingredient note.');
    $this->assertFieldById('edit-recipe-source', $source, 'Found the source source.');
    $this->assertFieldById('edit-recipe-instructions-value', $instructions, 'Found the source instructions.');
    $this->assertFieldById('edit-recipe-notes-value', $notes, 'Found the source notes.');
    $this->assertFieldById('edit-recipe-preptime', $preptime, 'Found the source prep time.');
    $this->assertFieldById('edit-recipe-cooktime', $cooktime, 'Found the source cook time.');
  }

  /**
   * Installs the specified language, or enables it if it is already installed.
   *
   * This was copied from the translation module test class.
   *
   * @param $language_code
   *   The language code to check.
   */
  function addLanguage($language_code) {
    // Check to make sure that language has not already been installed.
    $this->drupalGet('admin/config/regional/language');

    if (strpos($this->drupalGetContent(), 'enabled[' . $language_code . ']') === FALSE) {
      // Doesn't have language installed so add it.
      $edit = array();
      $edit['langcode'] = $language_code;
      $this->drupalPost('admin/config/regional/language/add', $edit, t('Add language'));

      // Make sure we are not using a stale list.
      drupal_static_reset('language_list');
      $languages = language_list('language');
      $this->assertTrue(array_key_exists($language_code, $languages), 'Language was installed successfully.');

      if (array_key_exists($language_code, $languages)) {
        $this->assertRaw(t('The language %language has been created and can now be used. More information is available on the <a href="@locale-help">help screen</a>.', array('%language' => $languages[$language_code]->name, '@locale-help' => url('admin/help/locale'))), 'Language has been created.');
      }
    }
    elseif ($this->xpath('//input[@type="checkbox" and @name=:name and @checked="checked"]', array(':name' => 'enabled[' . $language_code . ']'))) {
      // It's installed and enabled. No need to do anything.
      $this->assertTrue(true, 'Language [' . $language_code . '] already installed and enabled.');
    }
    else {
      // It's installed but not enabled. Enable it.
      $this->assertTrue(true, 'Language [' . $language_code . '] already installed.');
      $this->drupalPost(NULL, array('enabled[' . $language_code . ']' => TRUE), t('Save configuration'));
      $this->assertRaw(t('Configuration saved.'), 'Language successfully enabled.');
    }
  }

}
