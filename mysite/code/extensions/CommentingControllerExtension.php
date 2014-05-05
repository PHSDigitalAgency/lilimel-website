<?php
class CommentingControllerExtension extends Extension {
	private static $allowed_actions = array(
		'delete',
		'spam',
		'ham',
		'approve',
		'rss',
		'CommentsForm',
		'doPostComment',
		'doPreviewComment'
	);

	public function alterCommentForm(Form $form){
		$fields = $form->Fields();

		$name = $fields->dataFieldByName('Name')->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '');
		$email = $fields->dataFieldByName('Email')->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '');

		$fields->removeByName('Name');
		$fields->removeByName('Email');

		$fields->insertBefore(FieldGroup::create($name, $email)->addExtraClass('row'), 'URL');

		$form->Actions()->dataFieldByName('action_doPostComment')->addExtraClass('right');

		$form->transform(new FoundationFormTransformation());
		$form->addExtraClass('clearfix');
		
		// load the jquery
		Requirements::javascript('userforms/thirdparty/jquery-validate/jquery.validate.js');
		Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');
		Requirements::customScript(<<<JS
(function($) {
	jQuery(document).ready(function() {

		$("#Form_CommentsForm").validate({
			ignore: ':hidden',
			errorClass: "required",	
			errorPlacement: function(error, element) {
				if(element.is(":radio")) {
					error.insertAfter(element.closest("ul"));
				} else {
					error.insertAfter(element);
				}
			},
			focusCleanup: true,
			rules: { Form_CommentsForm_Name:{required:true}, Form_CommentsForm_Email:{required:true, email: true}, Form_CommentsForm_Comment:{required:true} },
			onfocusout : function(element) { this.element(element); }
		});
		$("#Form_CommentsForm label.left").each(function(){
			$("#"+$(this).attr("for")).attr("placeholder",$(this).text());$(this).remove();
		});
		Placeholders.init();
	});
})(jQuery);
JS
		);
	}
}