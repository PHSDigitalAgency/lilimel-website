;(function($){

		/*********************
		 *********************
		 **                 **
		 **     Default     **
		 **    validator    **
		 **                 **
		 *********************
		 *********************/
		jQuery.validator.setDefaults({
			messages: {
				required: "This field is required.",
				remote: "Please fix this field.",
				email: "Veuillez-entrer un email valide.",
				url: "Please enter a valid URL.",
				date: "Please enter a valid date.",
				dateISO: "Please enter a valid date (ISO).",
				number: "Please enter a valid number.",
				digits: "Please enter only digits.",
				creditcard: "Please enter a valid credit card number.",
				equalTo: "Please enter the same value again.",
				accept: "Please enter a value with a valid extension."
			}
		})
})