<?php

if(!defined('ABSPATH')) { exit; }

function bo_recipes_get_setting($setting_name, $default = false) {
	return apply_filters(__FUNCTION__, BO_Recipes_Components_Settings::get_setting($setting_name, $default), $setting_name, $default);
}

function bo_recipes_get_setting_border() {
	return bo_recipes_get_setting('border-width') . 'px ' . bo_recipes_get_setting('border-style') . ' ' . bo_recipes_get_setting('border-color');
}
