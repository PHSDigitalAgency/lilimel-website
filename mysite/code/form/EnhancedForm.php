<?php
class EnhancedMemberLoginForm extends MemberLoginForm {
	protected $template = "FoundationForm";
	public function forTemplate()
	{
		$this->transform(new FoundationFormTransformation());
		$this->addExtraClass('custom');

		$this->Fields()->fieldByName('Email')->setAttribute('required','true');
		$this->Fields()->fieldByName('Password')->setAttribute('required','true');

		Requirements::javascript('userforms/thirdparty/jquery-validate/jquery.validate.js');
		Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');
		Requirements::customScript(<<<JS
(function($) {
	jQuery(document).ready(function() {
		$("#EnhancedMemberLoginForm_LoginForm").validate({
			ignore: ':hidden',
			errorClass: "required",	
			errorPlacement: function(error, element){
				error.insertAfter(element);
			},
			focusCleanup: true,
			onfocusout : function(element) { this.element(element); },
			messages: {
				Email:{
					required: "Votre email est requis.",
					email: "Veuillez entrer une adresse email valide."
				},
				Password:{
					required: "Votre mot-de-passe est requis."
				}
			},
			rules: {
				Email:{required: true, email: true},
				Password:{required: true}
			}
		});
		$("#EnhancedMemberLoginForm_LoginForm label.left").each(function(){
			$("#"+$(this).attr("for")).attr("placeholder",$(this).text());$(this).remove();
		});
		// Placeholders.init();
	});
})(jQuery);
JS
		);

		return parent::forTemplate();
	}
	
}