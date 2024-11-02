var bo_recipeId = null;
jQuery(document).ready(function($) {
	$(document).on('click', '.bo-recipe-save', function(event) {
		event.preventDefault();
	    bo_recipeId = jQuery(this).attr("rel");
		var BO_HOST = 'https://www.bigoven.com',
			x = document.createElement('script');

		x.type = 'text/javascript';
		x.src = BO_HOST + '/assets/noexpire/js/getrecipe.js?' + (new Date().getTime() / 100000);

		document.getElementsByTagName('head')[0].appendChild(x);
	});

	$('.bo-recipe-save-container').show();
});
