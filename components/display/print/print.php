<?php

if(!defined('ABSPATH')) { exit; }

class BO_Recipes_Components_Display_Print {

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
			add_action('parse_request', array(__CLASS__, 'intercept_request'));
		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('generate_rewrite_rules', array(__CLASS__, 'add_print_rewrites'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
			add_filter('query_vars', array(__CLASS__, 'add_query_vars'));
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Rewrite Rules + Querying

	public static function add_print_rewrites($wp_rewrite) {
		$wp_rewrite->rules = array(
			'recipe/print/([0-9]+)/?' => 'index.php?bo-recipes-print=' . $wp_rewrite->preg_index(1),
		) + $wp_rewrite->rules;
	}

	public static function add_query_vars($vars) {
		$vars[] = 'bo-recipes-print';

		return $vars;
	}

	public static function intercept_request($wp) {
		if(isset($wp->query_vars['bo-recipes-print']) && is_numeric($wp->query_vars['bo-recipes-print'])) {
			$recipe = get_post($wp->query_vars['bo-recipes-print']);

			if(BO_RECIPES_RECIPE_TYPE == $recipe->post_type && 'publish' == $recipe->post_status) {
				$recipe_summary = wptexturize(convert_smilies(convert_chars(wpautop($recipe->post_content))));

				$print_templates = bo_recipes_get_print_templates();
				$print_template = bo_recipes_get_setting('print-template');

				$template_data = $print_templates[$print_template];

				$styleheets = array();
				foreach($template_data['stylesheets'] as $stylesheet) {
					$stylesheets[] = plugins_url("resources/{$stylesheet}", __FILE__);
				}

				include("views/{$print_template}");
				exit;
			} else {
				wp_die(__('The recipe could not be found'));
			}
		}
	}

	#endregion Rewrite Rules + Querying

	#region Template Tags

	public static function get_print_url($recipe_id) {
		$permalink = get_option('permalink_structure');

		if('' == $permalink) {
			$url = add_query_arg('bo-recipes-print', $recipe_id, home_url('/'));
		} else {
			$url = home_url(sprintf('/recipe/print/%s', $recipe_id));
		}

		return $url;
	}

	#endregion Template Tags

	#region Print Templates

	private static $templates = null;

	public static function get_print_templates() {
		if(is_null(self::$templates)) {
			$templates = array();

			$template_matcher = trailingslashit(path_join(dirname(__FILE__), 'views')) . '*.php';

			foreach(glob($template_matcher) as $template) {
				$template_contents = file_get_contents($template);
				$template_matches = array();
				$stylesheets_matches = array();

				if(preg_match('#Template:\s?(.+)$#mi', $template_contents, $template_matches)) {
					$name = trim($template_matches[1]);
				} else {
					continue;
				}

				if(preg_match('#Stylesheets:\s?(.+)$#mi', $template_contents, $stylesheets_matches)) {
					$stylesheets = array_map('trim', explode(',', $stylesheets_matches[1]));
				} else {
					$stylesheets = array();
				}

				$template_basename = basename($template);

				$templates[$template_basename] = compact('name', 'stylesheets');
			}


			self::$templates = $templates;
		}

		return self::$templates;
	}

	#endregion Print Templates
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Display_Print::init();
