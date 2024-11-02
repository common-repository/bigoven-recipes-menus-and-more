<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="bo-recipes-time-preparation"><?php _e('Preparation Time'); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="bo-recipes-time-preparation" name="bo-recipes[time-preparation]" placeholder="<?php _e('15 minutes'); ?>" value="<?php echo esc_attr($recipe_attributes['time-preparation']); ?>" />
				<p class="description"><?php _e('Enter the time you would spend preparing the recipe before cooking'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-time-cook"><?php _e('Cook Time'); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="bo-recipes-time-cook" name="bo-recipes[time-cook]" placeholder="<?php _e('45 minutes'); ?>" value="<?php echo esc_attr($recipe_attributes['time-cook']); ?>" />
				<p class="description"><?php _e('Enter the time this recipe requires for cooking'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-time-total"><?php _e('Total Time'); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="bo-recipes-time-total" name="bo-recipes[time-total]" placeholder="<?php _e('1 hour'); ?>" value="<?php echo esc_attr($recipe_attributes['time-total']); ?>" />
				<p class="description"><?php _e('Enter the total time required for this recipe'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-yield"><?php _e('Yield'); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="bo-recipes-yield" name="bo-recipes[yield]" placeholder="<?php _e('8 pieces'); ?>" value="<?php echo esc_attr($recipe_attributes['yield']); ?>" />
				<p class="description"><?php _e('Enter the quanity produced by this recipe, like how many people it satisfies or how many pieces can be made of it'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-serving-size"><?php _e('Serving Size'); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="bo-recipes-serving-size" name="bo-recipes[serving-size]" placeholder="<?php _e('1 piece'); ?>" value="<?php echo esc_attr($recipe_attributes['serving-size']); ?>" />
				<p class="description"><?php _e('Enter the recommended serving size - this is used for the nutrition information that follows'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-nutrition-calories"><?php _e('Calories'); ?></label></th>
			<td>
				<input type="text" class="small-text" id="bo-recipes-nutrition-calories" name="bo-recipes[nutrition-calories]" placeholder="<?php _e('580'); ?>" value="<?php echo esc_attr($recipe_attributes['nutrition-calories']); ?>" />
				<?php _e('calories per serving'); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-nutrition-fat"><?php _e('Fat (grams)'); ?></label></th>
			<td>
				<input type="text" class="small-text" id="bo-recipes-nutrition-fat" name="bo-recipes[nutrition-fat]" placeholder="<?php _e('40'); ?>" value="<?php echo esc_attr($recipe_attributes['nutrition-fat']); ?>" />
				<?php _e('grams of fat per serving'); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-nutrition-carbohydrates"><?php _e('Carbohydrates (grams)'); ?></label></th>
			<td>
				<input type="text" class="small-text" id="bo-recipes-nutrition-carbohydrates" name="bo-recipes[nutrition-carbohydrates]" placeholder="<?php _e('80'); ?>" value="<?php echo esc_attr($recipe_attributes['nutrition-carbohydrates']); ?>" />
				<?php _e('grams of carbohydrates per serving'); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-nutrition-protein"><?php _e('Protein (grams)'); ?></label></th>
			<td>
				<input type="text" class="small-text" id="bo-recipes-nutrition-protein" name="bo-recipes[nutrition-protein]" placeholder="<?php _e('25'); ?>" value="<?php echo esc_attr($recipe_attributes['nutrition-protein']); ?>" />
				<?php _e('grams of protein per serving'); ?>
			</td>
		</tr>
	</tbody>
</table>


