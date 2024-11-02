<p><?php _e('Embed this recipe in any page or post. Just copy and paste the following shortcode:'); ?></p>

<input type="text" class="code large-text bo-recipes-embed" readonly="readonly" value="<?php echo esc_attr(sprintf('[%s id="%d"]', BO_RECIPES_RECIPE_SHORTCODE, $recipe->ID)); ?>" />
