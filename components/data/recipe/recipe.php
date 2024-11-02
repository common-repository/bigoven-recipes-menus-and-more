<?php

if(!defined('ABSPATH')) { exit; }

// Recipes are stored as a custom post type with the following post_type key
if(!defined('BO_RECIPES_RECIPE_TYPE')) {
	define('BO_RECIPES_RECIPE_TYPE', 'bo-recipe');
}

// Recipes have a special icon defined by the dashicon icon font set
if(!defined('BO_RECIPES_RECIPE_ICON')) {
	define('BO_RECIPES_RECIPE_ICON', 'dashicons-admin-users');
}

// Recipe attributes are stored as post meta with the following meta key
if(!defined('BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY')) {
	define('BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY', 'clc-recipe-attributes');
}

class BO_Recipes_Components_Data_Recipe {

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
			add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
			add_action('dbx_post_sidebar', array(__CLASS__, 'output_meta_nonce'));
			add_action('save_post_' . BO_RECIPES_RECIPE_TYPE, array(__CLASS__, 'save_post'), 10, 2);
		} else {
			// Actions that only affect the frontend interface or operation

		}

		// Actions that affect both the administrative and frontend interface or operation
		add_action('after_setup_theme', array(__CLASS__, 'register_theme_support'), 10000001);
		add_action('init', array(__CLASS__, 'register_types'));
		add_action('wp_ajax_bo_recipes_create', array(__CLASS__, 'ajax_recipes_create'));
		add_action('wp_ajax_bo_recipes_get', array(__CLASS__, 'ajax_recipes_get'));
		add_action('wp_ajax_bo_recipes_search', array(__CLASS__, 'ajax_recipes_search'));
	}

	private static function _add_filters() {
		if(is_admin()) {
			// Filters that only affect the administrative interface or operation
			add_filter('bulk_post_updated_messages', array(__CLASS__, 'bulk_post_updated_messages'), 10, 2);
			add_filter('post_updated_messages', array(__CLASS__, 'post_updated_messages'));
		} else {
			// Filters that only affect the frontend interface or operation
		}

		// Filters that affect both the administrative and frontend interface or operation
		add_filter('bo_recipes_pre_get_recipe_attributes', array(__CLASS__, 'pre_get_recipe_attributes'), 0, 2);
		add_filter('bo_recipes_pre_set_recipe_attributes', array(__CLASS__, 'pre_set_recipe_attributes'), 0, 2);
	}

	#region AJAX Callbacks

	public static function ajax_recipes_create() {
		$data = stripslashes_deep($_POST);

		$post_arr = array(
			'post_content' => trim($data['summary']),
			'post_status' => 'publish',
			'post_title' => trim($data['name']),
			'post_type' => BO_RECIPES_RECIPE_TYPE,
		);

		if(isset($data['id']) && !empty($data['id'])) {
			$post_arr['ID'] = $data['id'];
		}

		$recipe_id = wp_insert_post($post_arr);

		if(!is_wp_error($recipe_id)) {
			self::_set_recipe_attributes($recipe_id, array(
				'ingredients' => $data['ingredients'],
				'instructions' => $data['instructions'],
			));

			wp_send_json(array(
				'error' => false,
				'shortcode' => bo_recipes_get_shortcode_for_recipe($recipe_id),
			));
		} else {
			wp_send_json(array(
				'error' => true,
				'errorMessage' => $post_id->get_error_message(),
			));
		}
	}

	public static function ajax_recipes_get() {
		$data = stripslashes_deep($_POST);

		$recipe_id = $data['id'];
		$recipe = get_post($data['id']);

		wp_send_json(array(
			'summary' => $recipe->post_content,
			'name' => $recipe->post_title,
			'ingredients' => bo_recipes_get_recipe_attribute($recipe_id, 'ingredients'),
			'instructions' => bo_recipes_get_recipe_attribute($recipe_id, 'instructions'),
		));
	}

	public static function ajax_recipes_search() {
		$data = stripslashes_deep($_POST);

		$paged = isset($data['page']) && is_numeric($data['page']) ? absint($data['page']) : 1;
		$query = isset($data['query']) ? trim($data['query']) : '';

		$results = new WP_Query(array(
			'paged' => $paged,
			'post_status' => 'publish',
			'post_type' => BO_RECIPES_RECIPE_TYPE,
			'posts_per_page' => 10,
			's' => $query,
		));

		$recipes = array();
		foreach($results->posts as $recipe) {
			$recipes[] = array(
				'name' => $recipe->post_title,
				'shortcode' => bo_recipes_get_shortcode_for_recipe($recipe->ID),
			);
		}

		wp_send_json(array(
			'error' => false,
			'page' => $paged,
			'pages' => $results->max_num_pages,
			'recipes' => $recipes,
		));
	}

	#endregion AJAX Callbacks

	#region Administrative Interface

	public static function bulk_post_updated_messages($bulk_messages, $bulk_counts) {
		$bulk_messages[BO_RECIPES_RECIPE_TYPE] = array(
			'updated'   => _n('%s recipe updated.', '%s recipes updated.', $bulk_counts['updated']),
			'locked'    => _n('%s recipe not updated, somebody is editing it.', '%s recipes not updated, somebody is editing them.', $bulk_counts['locked']),
			'deleted'   => _n('%s recipe permanently deleted.', '%s recipes permanently deleted.', $bulk_counts['deleted']),
			'trashed'   => _n('%s recipe moved to the Trash.', '%s recipes moved to the Trash.', $bulk_counts['trashed']),
			'untrashed' => _n('%s recipe restored from the Trash.', '%s recipes restored from the Trash.', $bulk_counts['untrashed']),
		);

		return $bulk_messages;
	}

	public static function enqueue_scripts() {
		$screen = get_current_screen();

		if(isset($screen->post_type) && BO_RECIPES_RECIPE_TYPE === $screen->post_type) {
			wp_enqueue_media();

			wp_enqueue_style('bo-recipes-recipe', plugins_url('resources/recipe.css', __FILE__), array(), BO_RECIPES_VERSION);
			wp_enqueue_script('bo-recipes-recipe', plugins_url('resources/recipe.js', __FILE__), array('jquery'), BO_RECIPES_VERSION, true);
		}
	}

	public static function post_updated_messages($messages) {
		$post = get_post(null);
		$post_id = $post->ID;

		$messages[BO_RECIPES_RECIPE_TYPE] = array(
			 0 => '',
			 1 => __('Recipe updated.'),
			 2 => __('Custom field updated.'),
			 3 => __('Custom field deleted.'),
			 4 => __('Recipe updated.'),
			 5 => isset($_GET['revision']) ? sprintf(__('Recipe restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
			 6 => __('Recipe published.'),
			 7 => __('Recipe saved.'),
			 8 => __('Recipe submitted.'),
			 9 => sprintf(__('Recipe scheduled for: <strong>%1$s</strong>.'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date))),
			10 => __('Recipe draft updated.'),
		);

		return $messages;
	}

	#endregion Administrative Interface

	#region Recipe Editing Interface

	public static function add_recipe_meta_boxes($recipe) {
		$screen = get_current_screen();

		add_meta_box('bo-recipes-recipe-basic', __('Recipe Information (Basic)'), array(__CLASS__, 'display_recipe_basic_meta_box'), $screen, 'normal', 'high');
		add_meta_box('bo-recipes-recipe-extended', __('Recipe Information (Extended)'), array(__CLASS__, 'display_recipe_extended_meta_box'), $screen, 'normal', 'high');

		if('publish' === $recipe->post_status) {
			add_meta_box('bo-recipes-recipe-embed', __('Embed Recipe'), array(__CLASS__, 'display_recipe_embed_meta_box'), $screen, 'side', 'low');
		}
	}

	public static function display_recipe_basic_meta_box($recipe) {
		$recipe_attributes = self::_get_recipe_attributes($recipe->ID);

		$settings_link = add_query_arg(array(
			'page' => BO_RECIPES_SETTINGS_PAGE,
			'post_type' => BO_RECIPES_RECIPE_TYPE,
		), admin_url('edit.php'));

		include('views/meta-boxes/recipe-basic.php');
	}

	public static function display_recipe_embed_meta_box($recipe) {
		include('views/meta-boxes/recipe-embed.php');
	}

	public static function display_recipe_extended_meta_box($recipe) {
		$recipe_attributes = self::_get_recipe_attributes($recipe->ID);

		include('views/meta-boxes/recipe-extended.php');
	}

	public static function output_meta_nonce($post) {
		if(BO_RECIPES_RECIPE_TYPE === $post->post_type) {
			wp_nonce_field('bo-recipes-save-recipe-attributes', 'bo-recipes-save-recipe-attributes-nonce');
		}
	}

	#endregion Recipe Editing Interface

	#region Recipe Registration

	public static function register_theme_support() {
		if(!current_theme_supports('post-thumbnails')) {
			add_theme_support('post-thumbnails', array(BO_RECIPES_RECIPE_TYPE));
		}
	}

	private static $recipe_error = null;

	/**
	 * Register all required content types for recipe functionality.
	 *
	 * @return void
	 */
	public static function register_types() {
		$recipe = self::_register_types_recipe();

		if(is_wp_error($recipe) && is_admin()) {
			self::$recipe_error = $recipe;

			add_action('admin_notices', array(__CLASS__, 'register_types_recipe_error'));
		}
	}

	/**
	 * Outputs a warning that registration for the recipe content type failed for
	 * some reason.
	 *
	 * @return void
	 */
	public static function register_types_recipe_error() {
		if(is_wp_error(self::$recipe_error)) {
			printf('<div class="error"><p>%s</p></div>', self::$recipe_error->get_error_message());
		}
	}

	/**
	 * Registers the recipe type.
	 *
	 * @return object|WP_Error the registered post type object, or an error object.
	 */
	private static function _register_types_recipe() {
		$labels = array(
			'name' => __('Recipes'),
			'menu_name' => __('Recipes'),
			'singular_name' => __('Recipe'),
			'add_new' => __('Add New'),
			'add_new_item' => __('Add New Recipe'),
			'edit_item' => __('Edit Recipe'),
			'new_item' => __('New Recipe'),
			'view_item' => __('View Recipe'),
			'search_items' => __('Search Recipes'),
			'not_found' => __('No recipes found.'),
			'not_found_in_trash' => __('No recipes found in Trash.'),
			'parent_item_colon' => null,
			'all_items' => __('All'),
		);

		$args = array(
			'labels'               => $labels,
			'description'          => __('Recipes can be easily displayed on your site using a shortcode and managed here.'),
			'public'               => false,
			'hierarchical'         => false,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => false,
			'show_in_admin_bar'    => false,
			'menu_position'        => null,
			'menu_icon'            => BO_RECIPES_RECIPE_ICON,
			'supports'             => array('title', 'thumbnail'),
			'register_meta_box_cb' => array(__CLASS__, 'add_recipe_meta_boxes'),
			'taxonomies'           => array(),
			'has_archive'          => false,
			'rewrite'              => false,
			'query_var'            => false,
			'can_export'           => true,
			'delete_with_user'     => false,
		);

		return register_post_type(BO_RECIPES_RECIPE_TYPE, $args);
	}

	#endregion Recipe Registration

	#region Recipe Meta Data

	public static function after_set_recipe_attributes($recipe_attributes, $recipe_id) {
		wp_cache_delete(BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY, $recipe_id);
	}

	public static function pre_get_recipe_attributes($recipe_attributes, $recipe_id) {
		$recipe_attributes = is_array($recipe_attributes) ? $recipe_attributes : array();
		$recipe_attributes_defaults = self::_get_recipe_attributes_defaults($recipe_id);

		return shortcode_atts($recipe_attributes_defaults, $recipe_attributes);
	}

	public static function pre_set_recipe_attributes($recipe_attributes, $recipe_id) {
		$recipe_attributes = is_array($recipe_attributes) ? $recipe_attributes : array();
		$recipe_attributes_defaults = self::_get_recipe_attributes_defaults($recipe_id);

		return shortcode_atts($recipe_attributes_defaults, $recipe_attributes);
	}

	public static function save_post($post_id, $post) {
		$data = stripslashes_deep($_POST);

		$recipe_attributes = isset($data['bo-recipes']) && is_array($data['bo-recipes']) ? $data['bo-recipes'] : array();
		$nonce_action = 'bo-recipes-save-recipe-attributes';
		$nonce_value = isset($data["{$nonce_action}-nonce"]) ? $data["{$nonce_action}-nonce"] : false;

		if(!$nonce_value || !wp_verify_nonce($nonce_value, $nonce_action) || wp_is_post_autosave($post) || wp_is_post_revision($post)) {
			return;
		}

		self::_set_recipe_attributes($post_id, $recipe_attributes);
	}

	private static function _get_recipe_attributes($recipe_id) {
		$recipe_id = is_null($recipe_id) && get_the_ID() ? get_the_ID() : $recipe_id;
		$recipe_attributes = wp_cache_get(BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY, $recipe_id);

		if(false === $recipe_attributes) {
			$recipe_attributes = apply_filters('bo_recipes_pre_get_recipe_attributes', get_post_meta($recipe_id, BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY, true), $recipe_id);
			wp_cache_set(BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY, $recipe_attributes, $recipe_id, BO_RECIPES_CACHE_PERIOD);
		}

		return $recipe_attributes;
	}

	private static function _get_recipe_attributes_defaults($recipe_id) {
		return apply_filters('bo_recipes_pre_get_recipe_attributes_defaults', array(
			'ingredients' => '',
			'instructions' => '',
			'nutrition-calories' => '',
			'nutrition-carbohydrates' => '',
			'nutrition-fat' => '',
			'nutrition-protein' => '',
			'serving-size' => '',
			'time-cook' => '',
			'time-preparation' => '',
			'time-total' => '',
			'yield' => '',

			// From ZipList
			'rating' => '',
			'notes' => '',
		), $recipe_id);
	}

	private static function _set_recipe_attributes($recipe_id, $recipe_attributes) {
		$recipe_attributes = apply_filters('bo_recipes_pre_set_recipe_attributes', $recipe_attributes, $recipe_id);

		update_post_meta($recipe_id, BO_RECIPES_RECIPE_ATTRIBUTES_META_KEY, $recipe_attributes);

		do_action('bo_recipes_after_set_recipe_attributes', $recipe_attributes, $recipe_id);
	}

	#endregion Recipe Meta Data

	#region Import

	public static function import_from_ziplist() {
		global $wpdb;

		$ziplist_ids_to_bo_recipe_ids = array();

		$table = $wpdb->prefix . 'amd_zlrecipe_recipes';

		$page = 1;
		$page_per = 25;

		do {
			$limit = sprintf('LIMIT %d, %d', (($page - 1) * $page_per), $page_per);
			$records = $wpdb->get_results("SELECT * FROM {$table} {$limit}");

			foreach($records as $record) {
				$recipe_id = self::_import_from_ziplist_single($record);

				if(!is_wp_error($recipe_id)) {
					$ziplist_ids_to_bo_recipe_ids[$record->recipe_id] = $recipe_id;
				}
			}

			$page++;
		} while(!empty($records));

		foreach($ziplist_ids_to_bo_recipe_ids as $ziplist_id => $bo_recipe_id) {
			$ziplist = sprintf('[amd-zlrecipe-recipe:%d]', $ziplist_id);
			$bo_recipes = bo_recipes_get_shortcode_for_recipe($bo_recipe_id);

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s) WHERE post_content LIKE %s", $ziplist, $bo_recipes, "%{$ziplist}%"));
		}
	}

	private static function _import_from_ziplist_single($record) {
		if(!function_exists('wp_generate_attachment_metadata')) {
			require_once(ABSPATH . 'wp-admin/includes/image.php');
		}

		$recipe_id = wp_insert_post(array(
			'post_content' => $record->summary,
			'post_status' => 'publish',
			'post_title' => $record->recipe_title,
			'post_type' => BO_RECIPES_RECIPE_TYPE,
		));

		if(!is_wp_error($recipe_id)) {
			if(!empty($record->recipe_image)) {
				// Let's try to get the image via HTTP
				$response = wp_remote_get($record->recipe_image);

				if(!is_wp_error($response)) {
					$bits = wp_remote_retrieve_body($response);
					$file = wp_upload_bits(basename($record->recipe_image), null, $bits);

					if(!isset($file['error']) || false === $file['error']) {
						$filename = $file['file'];

						$wp_filetype = wp_check_filetype($filename, null);
						$attachment = array(
							'post_content' => '',
							'post_mime_type' => $wp_filetype['type'],
							'post_parent' => $recipe_id,
							'post_status' => 'inherit',
							'post_title' => sanitize_file_name($filename),
						);

						$attachment_id = wp_insert_attachment($attachment, $filename, $post_id);
						$attach_data = wp_generate_attachment_metadata($attachment_id, $filename);
						wp_update_attachment_metadata($attachment_id, $attach_data);

						set_post_thumbnail($recipe_id, $attachment_id);
					}
				}
			}

			self::_set_recipe_attributes($recipe_id, array(
				'ingredients' => $record->ingredients,
				'instructions' => $record->instructions,
				'nutrition-calories' => preg_replace('#[^\d]#', '', $record->calories),
				'nutrition-carbohydrates' => '',
				'nutrition-fat' => preg_replace('#[^\d]#', '', $record->fat),
				'nutrition-protein' => '',
				'serving-size' => $record->serving_size,
				'time-cook' => self::_interval_to_string($record->cook_time),
				'time-preparation' => self::_interval_to_string($record->prep_time),
				'time-total' => self::_interval_to_string($record->total_time),
				'yield' => $record->yield,

				// From ZipList
				'rating' => $record->rating,
				'notes' => $record->notes,
			));
		}

		return $recipe_id;
	}

	private static function _interval_to_string($interval) {
		$parts = array();

		if(class_exists('DateInterval')) {
			try {
				$interval = new DateInterval($interval);

				$hours = $interval->h;
				$minutes = $interval->i;
				$seconds = $interval->s;

				if(!empty($hours)) {
					$parts[] = sprintf(_n('1 hour', '%s hours', $hours), $hours);
				}

				if(!empty($minutes)) {
					$parts[] = sprintf(_n('1 minute', '%s minutes', $minutes), $minutes);
				}

				if(!empty($seconds)) {
					$parts[] = sprintf(_n('1 second', '%s seconds', $seconds), $seconds);
				}
			} catch(Exception $e) {

			}
		} else {
			$data = array();

			if(preg_match('#^PT(\d+)H(\d+)M$#', $interval, $data)) {
				if(isset($data[1]) && !empty($data[1])) {
					$parts[] = sprintf(_n('1 hour', '%s hours', $hours), $data[1]);
				}

				if(isset($data[2]) && !empty($data[2])) {
					$parts[] = sprintf(_n('1 minute', '%s minutes', $minutes), $data[2]);
				}
			}
		}

		return implode(' ', $parts);
	}

	#endregion Import

	#region Template Tags

	public static function get_recipe_attribute($recipe_id, $attribute = null, $default = null) {
		$recipe_attributes = self::_get_recipe_attributes($recipe_id);

		return isset($recipe_attributes[$attribute]) ? $recipe_attributes[$attribute] : $default;
	}

	#endregion Template Tags
}

require_once('lib/template-tags.php');

BO_Recipes_Components_Data_Recipe::init();
