<div class="bo-recipes-placeholder" data-id="<?php echo esc_attr($recipe->ID); ?>">
	<div class="bo-recipes-placeholder-title"><?php echo esc_html($recipe->post_title); ?></div>

	<?php
	$ingredients = bo_recipes_get_ingredients($recipe->ID);
	$instructions = bo_recipes_get_instructions($recipe->ID);
	?>

	<div class="bo-recipes-stat"><strong><?php printf(_n('1 ingredient', '%s ingredients', count($ingredients)), number_format_i18n(count($ingredients))); ?></strong></div>
	<div class="bo-recipes-stat"><strong><?php printf(_n('1 instruction', '%s instructions', count($instructions)), number_format_i18n(count($instructions))); ?></strong></div>
</div>
