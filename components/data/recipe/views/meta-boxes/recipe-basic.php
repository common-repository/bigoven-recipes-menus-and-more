<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="bo-recipes-ingredients"><?php _e('Ingredients'); ?></label></th>
			<td>
				<textarea class="large-text" id="bo-recipes-ingredients" name="bo-recipes[ingredients]" placeholder="<?php _e('2 tablespoons butter'); ?>" rows="6"><?php echo esc_textarea($recipe_attributes['ingredients']); ?></textarea>
				<p class="description"><?php _e('Press enter after each ingredient, including the quantity and measurement - there is no need to number your ingredients. For headings, start the line with !'); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bo-recipes-instructions"><?php _e('Instructions'); ?></label></th>
			<td>
				<textarea class="large-text" id="bo-recipes-instructions" name="bo-recipes[instructions]" placeholder="<?php _e('Heat oven to 425 degrees'); ?>" rows="6"><?php echo esc_textarea($recipe_attributes['instructions']); ?></textarea>
				<p class="description"><?php _e('Press enter after each instruction - there is no need to number your instructions'); ?></p>
        		<p class="description"><?php _e('You can add links to [a website](http://foo.com/bar) You can add italics: *this text will be italic*, or boldface: **this text will be bold**');?></p>
		</td>
		</tr>

		<tr>
			<th scope="row"><label for="content"><?php _e('Summary'); ?></label></th>
			<td>
				<textarea class="large-text" id="content" name="content" placeholder="<?php _e('The most delicious apple pie you\'ll ever taste'); ?>" rows="6"><?php echo esc_textarea($recipe->post_content); ?></textarea>
				<p class="description"><?php _e('Provide a short introduction to or an accompany statement about this recipe'); ?></p>
			</td>
		</tr>
	</tbody>
</table>

<p class="description"><?php printf(__('Recipes belong to you. Let your readers make grocery lists and save recipes in the <a href="%s" target="_blank">settings</a> area.'), $settings_link); ?></p>
