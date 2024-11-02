<?php

if(!defined('ABSPATH')) { exit; }

class BO_Recipes_Components_Editor_Popup {

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
			add_action('media_upload_bo_recipes', array(__CLASS__, 'add_media_upload_output'));
		} else {
			// Actions that only affect the frontend interface or operation

		}

		// Actions that affect both the administrative and frontend interface or operation
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('media_upload_tabs', array(__CLASS__, 'add_media_upload_tabs'));
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
	}

	#region Popup Output

	public static function add_media_upload_output() {
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));

		return wp_iframe(array(__CLASS__, 'get_media_upload_output'));
	}

	public static function add_media_upload_tabs($tabs) {
		return array_merge($tabs, array('bo_recipes' => __('Recipes')));
	}

	public static function get_media_upload_output() {
		$add_new_url = add_query_arg(array('post_type' => BO_RECIPES_RECIPE_TYPE), admin_url('post-new.php'));

		include('views/popup.php');
	}

	#endregion Popup Output

	#region Scripts and Styles

	public static function enqueue_scripts($settings) {
		wp_enqueue_script('knockout', plugins_url('resources/vendor/knockout.min.js', __FILE__), array(), '3.2.0', true);

		wp_enqueue_script('bo-recipes-editor-popup', plugins_url('resources/editor-popup.js', __FILE__), array('jquery', 'knockout'), BO_RECIPES_VERSION, true);
		wp_enqueue_style('bo-recipes-editor-popup', plugins_url('resources/editor-popup.css', __FILE__), array('media'), BO_RECIPES_VERSION);

		wp_localize_script('bo-recipes-editor-popup', 'BO_Recipes_Editor_Popup', array(
			'textCreate' => __('Create'),
			'textUpdate' => __('Update'),
		));
	}

	#endregion Scripts and Styles
}

BO_Recipes_Components_Editor_Popup::init();
