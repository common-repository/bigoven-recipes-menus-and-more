jQuery(document).ready(function ($) {
    $(document).on('click', '.insert-bo-recipe', function (event) {
        event.preventDefault();

        BO_Recipes_Editor_Buttons.popup();
    });
});

BO_Recipes_Editor_Buttons.popup = function(recipeId) {
	var editor = BO_Recipes_Editor_Buttons.stateName,
		iframe = jQuery('.media-iframe iframe').get(0),
		recipeId = 'undefined' === typeof recipeId ? false : recipeId,
		recipeIdSet = false,
		workflow = wp.media.editor.add(editor, {
			frame: 'post',
			state: BO_Recipes_Editor_Buttons.stateName,
			title: BO_Recipes_Editor_Buttons.stateTitle
		});

	if(iframe) {
		var contentWindow = iframe && iframe.contentWindow ? iframe.contentWindow : iframe;

		if(contentWindow.BORVM && recipeId) {
			contentWindow.BORVM.recipeId(recipeId);
			recipeIdSet = true;
		}
	}

	if(recipeId && !recipeIdSet) {
		window.boRecipesRecipeId = recipeId;
		workflow.once('close', function() {
			window.boRecipesRecipeId = false;
		});
	}

	workflow.once('close', function() {
		var iframe = jQuery('.media-iframe iframe').get(0),
			contentWindow = iframe && iframe.contentWindow ? iframe.contentWindow : iframe;

		contentWindow.BORVM.reset();
	});

    wp.media.editor.open(editor, { title: BO_Recipes_Editor_Buttons.stateTitle });
};
