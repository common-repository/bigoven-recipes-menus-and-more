<?php

if(!defined('ABSPATH')) { exit; }

class BO_Recipes_Components_Editor_Buttons {

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
			add_action('media_buttons', array(__CLASS__, 'media_buttons'), 11);
			add_action('wp_enqueue_editor', array(__CLASS__, 'enqueue_editor_scripts'));
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
	}

	#region Editor Buttons

	public static function media_buttons($editor_id) {
		printf('<a href="#" class="button insert-bo-recipe add_media add_bo_recipe" data-editor="%s" title="%s"><span class="wp-media-buttons-icon"></span> %s</a>', $editor_id, esc_attr(__('Add Recipe')), esc_html(__('Add Recipe')));
	}

	#endregion Editor Buttons

	#region Scripts and Styles

	public static function enqueue_editor_scripts($settings) {
		wp_enqueue_script('bo-recipes-editor-buttons', plugins_url('resources/editor-buttons.js', __FILE__), array('jquery'), BO_RECIPES_VERSION, true);
		wp_enqueue_style('bo-recipes-editor-buttons', plugins_url('resources/editor-buttons.css', __FILE__), array(), BO_RECIPES_VERSION);

		wp_localize_script('bo-recipes-editor-buttons', 'BO_Recipes_Editor_Buttons', array(
			'stateName' => 'iframe:bo_recipes',
			'stateTitle' => __('Recipes'),
		));
	}

	#endregion Scripts and Styles
}

BO_Recipes_Components_Editor_Buttons::init();
