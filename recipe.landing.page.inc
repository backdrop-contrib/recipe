<?php

/**
 * @file
 * Page callbacks for recipe index page.
 */

/**
 * Page callback: Displays the Recipes Menu and list of recent recipes.
 */
function recipe_landing_page() {
  return theme('recipe_landing_page');
}

/**
 * Returns HTML for the recipe landing page.
 */
function theme_recipe_landing_page() {
  $config = config('recipe.settings');
  backdrop_set_title(t("Recipes Menu"));

  // Add the recipe.css file for this page.
  backdrop_add_css(backdrop_get_path('module', 'recipe') . '/recipe.css');

  // Get a list of current sub-menus and render them as a list.
  $item = menu_get_item();
  $content = system_admin_menu_block($item);

  if (user_access('create recipe content')) {
    $content[] = array(
      'title' => t('Add a new recipe'),
      'description' => '',
      'href' => 'node/add/recipe',
      'localized_options' => array('attributes' => array()),
    );
  }

  $output = '<div class="recipe_index_list">';
  $output .= theme('node_add_list', array('content' => $content));
  $output .= '</div>';

  // Render the recent recipes list.
  if ($config->get('recipe_recent_box_enable') == 1) {
    $node_list = recipe_get_latest($config->get('recipe_recent_display'));
    $build_node_list = node_title_list($node_list);
    $output .= '<div class="recipe_index_recent">';
    $output .= '<h2 class="title">' . $config->get('recipe_recent_box_title') . '</h2><div>' . backdrop_render($build_node_list) . '</div>';
    $output .= '</div>';
  }

  return $output;
}
