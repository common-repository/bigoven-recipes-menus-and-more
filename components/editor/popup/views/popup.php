<div class="bo-recipes-process-wrapper" style="display: none;">
	<h2 class="nav-tab-wrapper">
		<a href="#create" class="nav-tab" data-bind="click: setCreateStateActive, css: { 'nav-tab-active': createStateActive() }, text: createText">
			<?php _e('Create'); ?>
		</a>
        <a href="#search" class="nav-tab" data-bind="click: setSearchStateActive, css: { 'nav-tab-active': searchStateActive() }, visible: createNew">
			<?php _e('Insert'); ?>
		</a>
	</h2>

	<div class="bo-recipes-process attachment-details">
		<p><?php printf(__('Do you need help? <a href="%s" target="_blank">Click here</a> to learn more about the BigOven plugin for WordPress.'), 'http://support.bigoven.com/hc/en-us/articles/202384125'); ?></p>

		<div data-bind="visible: searchStateActive">
			<label class="setting">
				<input type="text" class="regular-text bo-recipes-input" id="bo-recipes-search-terms" value="" data-bind="event: { keypress: enterable }, value: searchQuery, valueUpdate: 'afterkeydown'" />
				<input type="button" class="button-primary bo-recipes-button bo-recipes-input" value="<?php _e('Search Recipes'); ?>" data-bind="click: search" />
				<span class="spinner bo-recipes-spinner" data-bind="style: { display: searchActive() ? 'inline-block' : 'none' }"></span>
			</label>

			<div class="tablenav top">
				<div class="tablenav-pages">
					<span class="pagination-links">
						<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: previousPage, css: { disabled: !hasPreviousPage() }">&lsaquo;</a>
						<span class="paging-input"><span data-bind="text: searchPage"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: searchPages"></span></span>
						<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: nextPage, css: { disabled: !hasNextPage() }">&rsaquo;</a>
					</span>
				</div>
			</div>

			<table class="widefat">
				<thead>
					<tr>
						<th scope="col" class="bo-recipes-name"><?php _e('Name'); ?></th>
						<th scope="col" class="bo-recipes-actions"><?php _e(''); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class="bo-recipes-image"><?php _e('Name'); ?></th>
						<th scope="col" class="bo-recipes-actions"><?php _e(''); ?></th>
					</tr>
				</tfoot>
				<tbody>
					<tr data-bind="visible: searchResultsEmpty">
						<td colspan="2"><?php _e('No recipes found'); ?></td>
					</tr>
					<!-- ko template: { foreach: searchResults, name: 'bo-recipes-search-result-template' } --><!-- /ko -->
				</tbody>
			</table>

			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<span class="pagination-links">
						<a class="prev-page" title="<?php _e('Go to the previous page'); ?>" href="#" data-bind="click: previousPage, css: { disabled: !hasPreviousPage() }">&lsaquo;</a>
						<span class="paging-input"><span data-bind="text: searchPage"></span> <?php _e('of'); ?> <span class="total-pages" data-bind="text: searchPages"></span></span>
						<a class="next-page" title="<?php _e('Go to the next page'); ?>" href="#" data-bind="click: nextPage, css: { disabled: !hasNextPage() }">&rsaquo;</a>
					</span>
				</div>
			</div>
		</div>

		<script type="text/html" id="bo-recipes-search-result-template">
		<tr>
			<td class="bo-recipes-name">
				<strong data-bind="text: name"></strong>
			</td>
			<td class="bo-recipes-actions">
				<a href="#" data-bind="click: $parent.insert"><?php _e('Insert'); ?></a>
			</td>
		</tr>
		</script>

		<div data-bind="visible: createStateActive">
			<div data-bind="visible: recipeRetrieved">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="bo-recipes-name"><?php _e('Name'); ?></label></th>
							<td>
								<input type="text" class="large-text" id="bo-recipes-name" placeholder="<?php _e('Grandma\'s Apple Pie'); ?>" value="" data-bind="value: createName" />
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="bo-recipes-ingredients"><?php _e('Ingredients'); ?></label></th>
							<td>
								<textarea class="large-text" id="bo-recipes-ingredients" placeholder="<?php _e('2 tablespoons butter'); ?>" rows="6" data-bind="value: createIngredients"><?php echo esc_textarea(''); ?></textarea>
								<p class="description"><?php _e('Press enter after each ingredient, including the quantity and measurement - there is no need to number your ingredients'); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="bo-recipes-instructions"><?php _e('Instructions'); ?></label></th>
							<td>
								<textarea class="large-text" id="bo-recipes-instructions" placeholder="<?php _e('Heat oven to 425 degrees'); ?>" rows="6" data-bind="value: createInstructions"><?php echo esc_textarea(''); ?></textarea>
								<p class="description"><?php _e('Press enter after each instruction - there is no need to number your instructions'); ?></p>
                <p class="description">
                  <?php _c('You can add links to [a website](http://foo/bar) or *this text will be italic* or **this text will be bold**'); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="content"><?php _e('Summary'); ?>
                  </label></th>
							<td>
								<textarea class="large-text" id="content"  placeholder="<?php _e('The most delicious apple pie you\'ll ever taste'); ?>" rows="6" data-bind="value: createSummary"><?php echo esc_textarea(''); ?></textarea>
								<p class="description"><?php _e('Provide a short introduction to or an accompany statement about this recipe'); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<p><?php printf(__('For more advanced recipe creation, please <a href="%s" target="_blank">add a new recipe</a>.'), $add_new_url); ?></p>

				<p class="submit">
					<input type="button" class="button button-primary" value="<?php echo esc_attr(__('Save and Insert')); ?>" data-bind="click: create" />
					<input type="button" class="button button-secondary" value="<?php echo esc_attr(__('Cancel')); ?>" data-bind="click: cancel, visible: recipeId()" />
					<span class="spinner bo-recipes-spinner" data-bind="style: { display: createActive() ? 'inline-block' : 'none' }"></span>
				</p>
			</div>

			<div data-bind="visible: retrievingRecipe">
				<p><?php _e('Your recipe details are being fetched. Please wait&hellip;'); ?></p>
			</div>
		</div>

		<div class="bo-recipes-process-clear"></div>
	</div>
</div>
