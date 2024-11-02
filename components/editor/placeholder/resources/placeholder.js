jQuery(document).ready(function($) {
	wp.mce.views.register(BO_Recipes_Editor_Placeholder.shortcode, {
		shortcodeData: {},
		shortcodeLoad: false,
		shortcodeId:   false,
		template:      wp.media.template('editor-seo-recipe'),

		getContent: function() {
			var self = this;

			self.shortcodeId = self.shortcode.attrs.named.id ? self.shortcode.attrs.named.id : false;

			if(self.shortcodeId && false === self.shortcodeLoad) {
				$.post(
					ajaxurl,
					{
						action: BO_Recipes_Editor_Placeholder.ajaxAction,
						id:     self.shortcodeId
					},
					function(data, status) {
						self.shortcodeData = data;
						self.shortcodeLoad = true;

						self.setContent(self.template(self.shortcodeData));
					},
					'json'
				);
			}

			return self.template(self.shortcodeData);
		},

		edit: function(data) {
			var options = wp.shortcode.next(BO_Recipes_Editor_Placeholder.shortcode, data);

			BO_Recipes_Editor_Buttons.popup(options.shortcode.attrs.named.id);
		}
	});
});
