<?php

if(!defined('ABSPATH')) { exit; }

// Recipes are stored as a custom post type with the following post_type key
if(!defined('BO_RECIPES_RECIPE_SHORTCODE')) {
	define('BO_RECIPES_RECIPE_SHORTCODE', 'seo_recipe');
}

class BO_Recipes_Components_Display_Shortcode {

	/**
	 * Initialize this component by setting up appropriate actions and filters,
	 * as well as adding shortcodes as necessary and performing any default
	 * tasks that are needed on site load.
	 */
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if(is_admin()) {
			// Actions that only affect the administrative interface or operation
		} else {
			// Actions that only affect the frontend interface or operation
			add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('init', array(__CLASS__, 'add_shortcodes'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Shortcode Registration

	public static function add_shortcodes() {
		add_shortcode(BO_RECIPES_RECIPE_SHORTCODE, array(__CLASS__, 'display_recipe'));
	}

	#endregion Shortcode Registration

	#region Shortcode Display

	public static function display_recipe($atts, $content = null) {
		$recipe_id = isset($atts['id']) && is_numeric($atts['id']) ? absint($atts['id']) : null;
		$recipe = $recipe_id ? get_post($recipe_id) : null;

		ob_start();

		if($recipe && BO_RECIPES_RECIPE_TYPE === $recipe->post_type && 'publish' === $recipe->post_status) {
			$recipe_summary = wptexturize(convert_smilies(convert_chars(wpautop($recipe->post_content))));
			$shortcode_template = bo_recipes_get_setting('shortcode-template');

			printf('<script type="application/ld+json">%s</script>', json_encode(self::get_recipe_json_data($recipe)));
			include(path_join(dirname(__FILE__), "views/{$shortcode_template}"));
		}

		return ob_get_clean();
	}

	#endregion Shortcode Display

	#region Scripts and Styles

	public static function enqueue_scripts() {
		if((is_singular() || is_page()) && has_shortcode(get_queried_object()->post_content, BO_RECIPES_RECIPE_SHORTCODE)) {
			$shortcode_templates = bo_recipes_get_shortcode_templates();
			$shortcode_template = bo_recipes_get_setting('shortcode-template');

			$template_data = $shortcode_templates[$shortcode_template];

			foreach($template_data['scripts'] as $script) {
				wp_enqueue_script('bo-recipes-shortcode-{$script}', plugins_url("resources/{$script}", __FILE__), array('jquery'), BO_RECIPES_VERSION, true);
			}

			foreach($template_data['stylesheets'] as $stylesheet) {
				wp_enqueue_style("bo-recipes-shortcode-{$stylesheet}", plugins_url("resources/{$stylesheet}", __FILE__), array(), BO_RECIPES_VERSION);
			}
		}
	}

	#endregion Scripts and Styles

	#region Shortcode Templates

	private static $templates = null;

	public static function get_shortcode_templates() {
		if(is_null(self::$templates)) {
			$templates = array();

			$template_matcher = trailingslashit(path_join(dirname(__FILE__), 'views')) . '*.php';

			foreach(glob($template_matcher) as $template) {
				$template_contents = file_get_contents($template);

				$template_matches = array();
				$scripts_matches = array();
				$stylesheets_matches = array();

				if(preg_match('#Template:\s?(.+)$#mi', $template_contents, $template_matches)) {
					$name = trim($template_matches[1]);
				} else {
					continue;
				}

				if(preg_match('#Scripts:\s?(.+)$#mi', $template_contents, $scripts_matches)) {
					$scripts = array_map('trim', explode(',', $scripts_matches[1]));
				} else {
					$scripts = array();
				}

				if(preg_match('#Stylesheets:\s?(.+)$#mi', $template_contents, $stylesheets_matches)) {
					$stylesheets = array_map('trim', explode(',', $stylesheets_matches[1]));
				} else {
					$stylesheets = array();
				}

				$template_basename = basename($template);

				$templates[$template_basename] = compact('name', 'scripts', 'stylesheets');
			}


			self::$templates = $templates;
		}

		return self::$templates;
	}

	#endregion Shortcode Templates

	#region JSON Data

	private static function get_recipe_json_data($recipe) {
		$data = array(
			'@context' => 'http://schema.org',
			'@type' => 'Recipe',
			'name' => $recipe->post_title,
			'author' => array(
				'@type' => 'Person',
				'name' => get_the_author_meta('display_name', $recipe->post_author),
			),
			'datePublished' => get_the_time('Y-m-d', $recipe->ID),
			'description' => $recipe->post_content,
			'recipeIngredient' => bo_recipes_get_ingredients($recipe->ID),
			'nutrition' => array(
				'@type' => 'NutritionInformation',
			),
		);

		if(has_post_thumbnail($recipe->ID)) {
			$image_src = wp_get_attachment_image_src(get_post_thumbnail_id($recipe->ID), 'full');

			$data['image'] = $image_src[0];
		}

		if(($instructions = array_filter(array_map('trim', bo_recipes_get_instructions($recipe->ID))))) {
			$data['recipeInstructions'] = '';

			foreach(array_values($instructions) as $instruction_index => $instruction) {
				$data['recipeInstructions'] .= sprintf('%d. %s', $instruction_index + 1, $instruction) . "\n";
			}
		}

		if(($yield = bo_recipes_get_recipe_attribute($recipe->ID, 'yield'))) {
			$data['recipeYield'] = $yield;
		}

		if($time_cook = bo_recipes_get_time_cook_duration($recipe->ID)) {
			$data['cookTime'] = $time_cook;
		}

		if($time_prep = bo_recipes_get_time_preparation_duration($recipe->ID)) {
			$data['prepTime'] = $time_prep;
		}

		if($time_total = bo_recipes_get_time_total_duration($recipe->ID)) {
			$data['totalTime'] = $time_total;
		}

		if(($nutrition_calories = bo_recipes_get_nutrition_calories($recipe->ID))) {
			$data['nutrition']['calories'] = sprintf('%d %s', $nutrition_calories, _n('calorie', 'calories', $nutrition_calories));
		}

		if(($nutrition_carbohydrates = bo_recipes_get_nutrition_carbohydrates($recipe->ID))) {
			$data['nutrition']['carbohydrateContent'] = sprintf('%d %s', $nutrition_carbohydrates, _n('gram', 'grams', $nutrition_carbohydrates));
		}

		if(($nutrition_fat = bo_recipes_get_nutrition_fat($recipe->ID))) {
			$data['nutrition']['fatContent'] = sprintf('%d %s', $nutrition_fat, _n('gram', 'grams', $nutrition_fat));
		}

		if(($nutrition_protein = bo_recipes_get_nutrition_protein($recipe->ID))) {
			$data['nutrition']['proteinContent'] = sprintf('%d %s', $nutrition_protein, _n('gram', 'grams', $nutrition_protein));
		}

		return $data;
	}

	#endregion JSON Data
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Display_Shortcode::init();
