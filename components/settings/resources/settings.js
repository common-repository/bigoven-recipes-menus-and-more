jQuery(document).ready(function($) {
	$('.bo-recipes-colorpicker').wpColorPicker({
		defaultColor: '#cccccc'
	})

	$('#bo-recipes-border-style').siblings('.wp-picker-container').css({
		'margin-top': '2px',
		'vertical-align': 'top'
	}).find('.wp-picker-holder').css('position', 'absolute');
});
