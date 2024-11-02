<?php

if(!defined('ABSPATH')) { exit; }

// Settings name
if(!defined('BO_RECIPES_SETTINGS_NAME')) {
	define('BO_RECIPES_SETTINGS_NAME', 'bo-recipes');
}

// Settings page slug
if(!defined('BO_RECIPES_SETTINGS_PAGE')) {
	define('BO_RECIPES_SETTINGS_PAGE', 'bo-recipes-settings');
}

class BO_Recipes_Components_Settings {

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
			add_action('admin_init', array(__CLASS__, 'register_setting'));
			add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
		} else {
			// Actions that only affect the frontend interface or operation
		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
		add_filter('bo_recipes_pre_get_settings', array(__CLASS__, 'pre_get_settings'));
	}

	#region Administrative Interface

	public static function add_settings_page() {
		$settings = add_submenu_page(sprintf('edit.php?post_type=%s', BO_RECIPES_RECIPE_TYPE), __('Recipes - Settings'), __('Settings'), 'manage_options', BO_RECIPES_SETTINGS_PAGE, array(__CLASS__, 'display_settings_page'));

		add_action("load-{$settings}", array(__CLASS__, 'load'));
	}

	public static function display_settings_page() {
		$settings = self::_get_settings();

		$settings_url = add_query_arg(array(
			'post_type' => BO_RECIPES_RECIPE_TYPE,
			'page' => BO_RECIPES_SETTINGS_PAGE,
		), admin_url('edit.php'));
		$support_url = 'http://support.bigoven.com/hc/en-us/articles/202384255';

		$border_styles = array(
			'none' => __('None'),
			'solid' => __('Solid'),
			'dashed' => __('Dashed'),
			'dotted' => __('Dotted'),
		);

		include('views/settings.php');
	}

	#endregion Administrative Interface

	#region Settings Fields

	private static function _get_setting_id($name) {
		return BO_RECIPES_SETTINGS_NAME . '-' . $name;
	}

	private static function _get_setting_name($name) {
		return BO_RECIPES_SETTINGS_NAME . '[' . $name . ']';
	}

	#endregion Settings Fields

	#region Actions

	public static function load() {
		$data = stripslashes_deep($_POST);

		if(isset($data['bo-recipes-import-ziplist-recipes-nonce']) && wp_verify_nonce($data['bo-recipes-import-ziplist-recipes-nonce'], 'bo-recipes-import-ziplist-recipes')) {
			set_time_limit(0);

			BO_Recipes_Components_Data_Recipe::import_from_ziplist();

			$settings = self::_set_settings(array_merge(self::_get_settings(), array('imported-ziplist-recipes' => 'yes')));

			add_settings_error('general', 'settings_updated', __('ZipList recipes imported successfully.'), 'updated');
			set_transient('settings_errors', get_settings_errors(), 30);

			bo_recipes_redirect(add_query_arg(array(
				'post_type' => BO_RECIPES_RECIPE_TYPE,
				'page' => BO_RECIPES_SETTINGS_PAGE,
				'settings-updated' => true,
			), admin_url('edit.php')));
		}

		wp_enqueue_script('bo-recipes-settings', plugins_url('resources/settings.js', __FILE__), array('jquery', 'wp-color-picker'), BO_RECIPES_VERSION, true);
		wp_enqueue_style('wp-color-picker');
	}

	#endregion Actions

	#region Settings

	public static function get_setting($settings_key, $default = null) {
		$settings = self::_get_settings();

		return isset($settings[$settings_key]) ? $settings[$settings_key] : $default;
	}

	public static function pre_get_settings($settings) {
		$settings = is_array($settings) ? $settings : array();

		return shortcode_atts(self::_get_settings_defaults(), $settings);
	}

	public static function register_setting() {
		register_setting(BO_RECIPES_SETTINGS_PAGE, BO_RECIPES_SETTINGS_NAME, array(__CLASS__, 'sanitize_settings'));
	}

	public static function sanitize_settings($settings) {
		wp_cache_delete(BO_RECIPES_SETTINGS_NAME);

		return shortcode_atts(self::_get_settings_defaults(), $settings);
	}

	private static function _get_settings() {
		$settings = apply_filters('bo_recipes_pre_get_settings', get_option(BO_RECIPES_SETTINGS_NAME, self::_get_settings_defaults()));

		wp_cache_set(BO_RECIPES_SETTINGS_NAME, $settings, null, BO_RECIPES_CACHE_PERIOD);

		return $settings;
	}

	private static function _get_settings_defaults() {
		return apply_filters('bo_recipes_pre_get_settings_defaults', array(
			// Display
			'number-instructions' => 'no',
			'shortcode-template' => 'basic.php',
			'print-template' => 'basic.php',

			/// Border
			'border-style' => 'none',
			'border-color' => '#cccccc',
			'border-width' => '0',

			// Enhance
			'save-recipe' => 'yes',
			'feature-recipe' => 'no',

			// Import from ZipList
			'imported-ziplist-recipes' => 'no',
		));
	}

	private static function _set_settings($settings) {
		$settings = apply_filters('bo_recipes_pre_set_settings', $settings);

		update_option(BO_RECIPES_SETTINGS_NAME, $settings);

		return $settings;
	}

	#endregion Settings
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Settings::init();
