<?php

if(!defined('ABSPATH')) { exit; }

function bo_recipes_has_recipe_attribute($recipe_id = null, $recipe_attribute = null) {
	return apply_filters(__FUNCTION__, !!bo_recipes_get_recipe_attribute($recipe_id, $recipe_attribute, false), $recipe_id, $recipe_attribute);
}

function bo_recipes_get_recipe_attribute($recipe_id = null, $recipe_attribute = null, $default = null) {
	return apply_filters(__FUNCTION__, BO_Recipes_Components_Data_Recipe::get_recipe_attribute($recipe_id, $recipe_attribute, $default), $recipe_id, $recipe_attribute, $default);
}

#region Ingredients and Instructions Template Tags

function bo_recipes_get_ingredients($recipe_id = null) {
	return apply_filters(__FUNCTION__, array_map('bo_recipes_markdown', array_map('sanitize_text_field', array_map('trim', explode("\n", bo_recipes_get_recipe_attribute($recipe_id, 'ingredients'))))), $recipe_id);
}

function bo_recipes_get_instructions($recipe_id = null) {
	return apply_filters(__FUNCTION__, array_map('bo_recipes_markdown', array_map('sanitize_text_field', array_map('trim', explode("\n", bo_recipes_get_recipe_attribute($recipe_id, 'instructions'))))), $recipe_id);
}

function bo_recipes_markdown($text) {
	if(!class_exists('Markdown_Parser') && !function_exists('Markdown')) {
		require_once(path_join(BO_RECIPES_PATH, 'lib/markdown.php'));
	}

	return function_exists('Markdown') ? bo_recipes_allow_tags(Markdown($text)) : bo_recipes_allow_tags($text);
}

function bo_recipes_allow_tags($text) {
	$tags = apply_filters(__FUNCTION__, '<b><i><em><strong><a>');

	return strip_tags($text, $tags);
}

#endregion Ingredients and Instructions Template Tags

#region Duration Template Tags

function bo_recipes_get_time_cook($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'time-cook')), $recipe_id);
}

function bo_recipes_get_time_cook_duration($recipe_id = null) {
	return apply_filters(__FUNCTION__, bo_recipes_time_to_iso8601_duration(strtotime(bo_recipes_get_time_cook($recipe_id), 0)), $recipe_id);
}

function bo_recipes_get_time_preparation($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'time-preparation')), $recipe_id);
}

function bo_recipes_get_time_preparation_duration($recipe_id = null) {
	return apply_filters(__FUNCTION__, bo_recipes_time_to_iso8601_duration(strtotime(bo_recipes_get_time_preparation($recipe_id), 0)), $recipe_id);
}

function bo_recipes_get_time_total($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'time-total')), $recipe_id);
}

function bo_recipes_get_time_total_duration($recipe_id = null) {
	return apply_filters(__FUNCTION__, bo_recipes_time_to_iso8601_duration(strtotime(bo_recipes_get_time_total($recipe_id), 0)), $recipe_id);
}

#endregion Duration Template Tags

#region Nutrition Template Tags

function bo_recipes_get_nutrition_calories($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'nutrition-calories')), $recipe_id);
}

function bo_recipes_get_nutrition_carbohydrates($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'nutrition-carbohydrates')), $recipe_id);
}

function bo_recipes_get_nutrition_fat($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'nutrition-fat')), $recipe_id);
}

function bo_recipes_get_nutrition_protein($recipe_id = null) {
	return apply_filters(__FUNCTION__, sanitize_text_field(bo_recipes_get_recipe_attribute($recipe_id, 'nutrition-protein')), $recipe_id);
}

#endregion Nutrition Template Tags

#region Duration

// Thanks to http://codepad.org/1fHNlB6e
function bo_recipes_time_to_iso8601_duration($time) {
	$units = array(
		"Y" => 365*24*3600,
		"D" =>     24*3600,
		"H" =>        3600,
		"M" =>          60,
		"S" =>           1,
	);

	$str = "P";
	$istime = false;

	foreach ($units as $unitName => &$unit) {
		$quot  = intval($time / $unit);
		$time -= $quot * $unit;
		$unit  = $quot;
		if ($unit > 0) {
			if (!$istime && in_array($unitName, array("H", "M", "S"))) { // There may be a better way to do this
				$str .= "T";
				$istime = true;
			}
			$str .= strval($unit) . $unitName;
		}
	}

	return $str;
}

#endregion Duration