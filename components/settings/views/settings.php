<div class="wrap">
	<h2><?php _e('Recipes - Settings'); ?></h2>
	<?php settings_errors(); ?>

	<p>
  <?php printf(__('This plugin is &copy;2015 BigOven, but all your content is your own, and always will be. Read our <a href="%s" target="_blank">Pledge to Food Bloggers</a>.'), 'http://wordpress.bigoven.com/index.php/our-pledge-to-food-bloggers-2/'); ?></p>

	<p><?php printf(__('Do you need help? <a href="%s" target="_blank">Learn more</a> about the BigOven plugin for WordPress, or email us at bloggers@bigoven.com'), 'http://wordpress.bigoven.com'); ?></p>

	<form action="options.php" method="post">
		<h3><?php _e('Display'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('number-instructions-yes'); ?>"><?php _e('Instructions'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('number-instructions-no'); ?>" name="<?php echo self::_get_setting_name('number-instructions'); ?>" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['number-instructions']); ?> id="<?php echo self::_get_setting_id('number-instructions-yes'); ?>" name="<?php echo self::_get_setting_name('number-instructions'); ?>" value="yes" />
							<?php _e('Each recipe instruction on my site should be numbered'); ?>
						</label>
						<p class="description"><?php _e('Checking this box will display your recipe instructions in a sequentially numbered list'); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Border Style'); ?></th>
					<td>
						<select id="<?php echo self::_get_setting_id('border-width'); ?>" name="<?php echo self::_get_setting_name('border-width'); ?>">
							<?php foreach(range(0, 10) as $border_width) { ?>
							<option <?php selected($border_width, $settings['border-width']); ?> value="<?php echo esc_attr($border_width); ?>"><?php echo esc_html($border_width); ?>px</option>
							<?php } ?>
						</select>

						<select id="<?php echo self::_get_setting_id('border-style'); ?>" name="<?php echo self::_get_setting_name('border-style'); ?>">
							<?php foreach($border_styles as $border_style => $border_style_nice) { ?>
							<option <?php selected($border_style, $settings['border-style']); ?> value="<?php echo esc_attr($border_style); ?>"><?php echo esc_html($border_style_nice); ?></option>
							<?php } ?>
						</select>

						<input type="text" class="colorpicker bo-recipes-colorpicker" id="<?php echo self::_get_setting_id('border-color'); ?>" name="<?php echo self::_get_setting_name('border-color'); ?>" value="<?php echo esc_attr($settings['border-color']); ?>" />

						<p class="description"><?php _e('The border style you select will appear around all your recipes'); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('shortcode-template'); ?>"><?php _e('Recipe Template'); ?></label></th>
					<td>
						<select id="<?php echo self::_get_setting_id('shortcode-template'); ?>" name="<?php echo self::_get_setting_name('shortcode-template'); ?>">
							<?php foreach(bo_recipes_get_shortcode_templates() as $shortcode_template_key => $shortcode_template_data) { ?>
							<option <?php selected($shortcode_template_key, $settings['shortcode-template']); ?> value="<?php echo esc_attr($shortcode_template_key); ?>"><?php echo esc_html($shortcode_template_data['name']); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('print-template'); ?>"><?php _e('Print Template'); ?></label></th>
					<td>
						<select id="<?php echo self::_get_setting_id('print-template'); ?>" name="<?php echo self::_get_setting_name('print-template'); ?>">
							<?php foreach(bo_recipes_get_print_templates() as $print_template_key => $print_template_data) { ?>
							<option <?php selected($print_template_key, $settings['print-template']); ?> value="<?php echo esc_attr($print_template_key); ?>"><?php echo esc_html($print_template_data['name']); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e('Enhance My Recipes'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('save-recipe-yes'); ?>"><?php _e('Save Recipe &amp;<br />Make Grocery List'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('save-recipe-no'); ?>" name="<?php echo self::_get_setting_name('save-recipe'); ?>" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['save-recipe']); ?> id="<?php echo self::_get_setting_id('save-recipe-yes'); ?>" name="<?php echo self::_get_setting_name('save-recipe'); ?>" value="yes" />
							<?php _e('Let readers save your recipes or add items to mobile grocery list'); ?>
						</label>
						<p class="description"><?php printf(__('Checking this box will allow your readers to save the recipes they’d like to their own recipe collection, and make mobile-friendly grocery lists, with a full link back to your site. <a href="%s">Learn more</a>.'), 'http://wordpress.bigoven.com'); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>"><?php _e('Feature My Recipes<br />(coming soon)'); ?></label></th>
					<td>
						<input type="hidden" id="<?php echo self::_get_setting_id('feature-recipe-no'); ?>" name="" value="no" />

						<label>
							<input type="checkbox" <?php checked('yes', $settings['feature-recipe']); ?> id="<?php echo self::_get_setting_id('feature-recipe-yes'); ?>" name="<?php echo self::_get_setting_name('feature-recipe'); ?>" value="yes" />
							<?php _e('Let BigOven feature your recipes in the popular <a href="http://www.bigoven.com/mobile">BigOven mobile apps</a>'); ?>
						</label>
						<p class="description"><?php printf(__('BigOven is always looking for the best food writing on the web to feature.  In 2015 and beyond, we will be displaying select articles of great food writing and recipe ideas - full blog articles from third-party blogs - in a "Best of Web" section within the popular mobile apps (11+ million downloads at this writing).  To qualify for this exposure, your blog must be mobile-friendly (check on iPhone in particular) and you must have the "Save Recipe" option above enabled on your blog.  Checking this option will let BigOven’s editorial team know you\'d welcome no-cost additional exposure. <a href="%s" target="_blank">Learn more</a>.'), $support_url); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php printf(__('<h3>Got ideas?</h3><p>We want BigOven to be the ideal food-blogger\'s companion, helping you make a great site and delighting your readers with tools that make planning and organizing easier. Got an idea or suggested improvement? <a href="%s" target="_blank">Let us know</a>.</p>'), 'http://www.bigoven.com/site/comments'); ?>
		</p>

		<p class="submit">
			<input type="hidden" name="<?php echo self::_get_setting_name('imported-ziplist-recipes'); ?>" value="<?php echo 'yes' === $settings['imported-ziplist-recipes'] ? 'yes' : 'no'; ?>" />

			<?php settings_fields(BO_RECIPES_SETTINGS_PAGE); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>

	<?php if('no' === $settings['imported-ziplist-recipes']) { ?>

	<form action="<?php echo esc_attr(esc_url($settings_url)); ?>" method="post">
		<h3><?php _e('Import from ZipList'); ?></h3>

		<p>
			<strong><?php _e('Important:'); ?></strong>
			<?php printf(__('Before importing from ZipList Recipes, please back up your MySQL database. This makes irreversible changes to post content in order to replace ZipList Recipes shortcodes with BigOven Recipes shortcodes. <a href="%s" target="_blank">Learn more</a>.'), $support_url);; ?>
		</p>

		<p class="submit">
			<?php wp_nonce_field('bo-recipes-import-ziplist-recipes', 'bo-recipes-import-ziplist-recipes-nonce'); ?>
			<input type="submit" class="button button-primary" id="bo-recipes-import-ziplist-recipes" value="<?php _e('Import from ZipList'); ?>" />
		</p>
	</form>

	<?php } ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

});
</script>
