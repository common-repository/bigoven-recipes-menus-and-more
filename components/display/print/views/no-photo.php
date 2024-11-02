<?php
/**
 * Template: No-photo
 * Stylesheets: no-photo.css
 */
?>
<!doctype html>
<html>
	<head>
		<title><?php printf(__('"%s" from %s'), esc_html($recipe->post_title), esc_html(get_bloginfo('name'))); ?></title>

		<?php foreach($stylesheets as $stylesheet) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url($stylesheet); ?>" />
		<?php } ?>
    </head>
	<body onload="window.print()">
		<div class="bo-recipe" style="border: <?php echo bo_recipes_get_setting_border(); ?>">
			<div class="hrecipe" rel="<?php echo esc_html($recipe->ID); ?>">
				<h1 class="fn"><?php echo esc_html($recipe->post_title); ?></h1>



				<?php if($recipe->post_content) { ?>
				<div class="summary">
					<?php echo $recipe_summary; ?>
				</div>
				<?php } ?>

				<?php if(bo_recipes_has_recipe_attribute($recipe->ID, 'yield')) { ?>
				<p class="yield-container">
					<?php _e('Yields'); ?>: <span class="yield"><?php echo esc_html(bo_recipes_get_recipe_attribute($recipe->ID, 'yield')); ?></span>
				</p>
				<?php } ?>

				<?php if(($ingredients = bo_recipes_get_ingredients($recipe->ID))) { ?>
				<h2><?php _e('Ingredients'); ?></h2>

				<ul class="ingredient-list">
					<?php foreach($ingredients as $ingredient) { ?>
                    <?php if($ingredient!="") {?>
		            <?php if(bo_recipes_starts_with($ingredient,'!')){ ?>
					<li class="ingredHeading"><?php echo trim(str_replace("!","",$ingredient)); ?></li>
		            <?php } else { ?>
					<li class="ingredient"><?php echo trim($ingredient); ?></li>
		   			<?php } ?>
					<?php } ?>
                    <?php } ?>
				</ul>
				<?php } ?>

				<?php if(($instructions = bo_recipes_get_instructions($recipe->ID))) { ?>
				<h2><?php _e('Instructions'); ?></h2>

				<?php if('yes' === bo_recipes_get_setting('number-instructions')) { ?>
				<ol class="instructions">
					<?php foreach($instructions as $instruction) { ?>
					<li class="instruction"><?php echo trim($instruction); ?></li>
					<?php } ?>
				</ol>
				<?php } else { ?>
				<div class="instructions">
					<?php foreach($instructions as $instruction) { ?>
					<p class="instruction"><?php echo trim($instruction); ?></p>
					<?php } ?>
				</div>
				<?php } ?>

				<?php } ?>

				<?php
				$time_cook = bo_recipes_get_time_cook($recipe->ID);
				$time_preparation = bo_recipes_get_time_preparation($recipe->ID);
				$time_total = bo_recipes_get_time_total($recipe->ID);
				?>

				<?php if($time_cook || $time_preparation || $time_total) { ?>
				<ul class="duration-list">
					<?php if($time_preparation) { ?>
					<li class="duration"><span class="value-title" title="<?php echo esc_attr(bo_recipes_get_time_preparation_duration($recipe->ID)); ?>"><?php _e('Preparation time'); ?>:</span> <?php echo esc_html($time_preparation); ?></li>
					<?php } ?>

					<?php if($time_cook) { ?>
					<li class="duration"><span class="value-title" title="<?php echo esc_attr(bo_recipes_get_time_cook_duration($recipe->ID)); ?>"><?php _e('Cook time'); ?>:</span> <?php echo esc_html($time_cook); ?></li>
					<?php } ?>

					<?php if($time_total) { ?>
					<li class="duration"><span class="value-title" title="<?php echo esc_attr(bo_recipes_get_time_total_duration($recipe->ID)); ?>"><?php _e('Total time'); ?>:</span> <?php echo esc_html($time_total); ?></li>
					<?php } ?>
				</ul>
				<?php } ?>

				<?php
				$nutrition_calories = bo_recipes_get_nutrition_calories($recipe->ID);
				$nutrition_carbohydrates = bo_recipes_get_nutrition_carbohydrates($recipe->ID);
				$nutrition_fat = bo_recipes_get_nutrition_fat($recipe->ID);
				$nutrition_protein = bo_recipes_get_nutrition_protein($recipe->ID);
				?>

				<?php if($nutrition_calories || $nutrition_fat || $nutrition_carbohydrates || $nutrition_protein) { ?>
				<h3>Nutrition</h3>
				<ul class="nutrition-list">
					<?php if($nutrition_calories) { ?>
					<li class="nutrition"><span class="value-title" title="<?php echo esc_attr($nutrition_calories); ?>"><?php _e('Calories'); ?>:</span> <?php echo esc_html($nutrition_calories); ?></li>
					<?php } ?>

					<?php if($nutrition_fat) { ?>
					<li class="nutrition"><span class="value-title" title="<?php echo esc_attr($nutrition_fat); ?>"><?php _e('Fat'); ?>:</span> <?php echo esc_html($nutrition_fat); ?> <?php _e('grams'); ?></li>
					<?php } ?>

					<?php if($nutrition_carbohydrates) { ?>
					<li class="nutrition"><span class="value-title" title="<?php echo esc_attr($nutrition_carbohydrates); ?>"><?php _e('Carbs'); ?>:</span> <?php echo esc_html($nutrition_carbohydrates); ?> <?php _e('grams'); ?></li>
					<?php } ?>

					<?php if($nutrition_protein) { ?>
					<li class="nutrition"><span class="value-title" title="<?php echo esc_attr($nutrition_protein); ?>"><?php _e('Protein'); ?>:</span> <?php echo esc_html($nutrition_protein); ?> <?php _e('grams'); ?></li>
					<?php } ?>
				</ul>
				<?php } ?>
			</div>
		</div>

	</body>
</html>