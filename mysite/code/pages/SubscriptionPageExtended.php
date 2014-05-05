<?php
class SubscriptionPageExtended extends SubscriptionPage {
	
}

class SubscriptionPageExtended_Controller extends SubscriptionPage_Controller {

	private static $allowed_actions = array(
		'index',
		'subscribeverify',
		'submitted',
		'completed',
		'Form'
	);

	/*public function Form(){
		$form = parent::Form();

		if(isset($form)){
			$form->Actions()->fieldByName('action_doSubscribe')->addExtraClass('right');
			$form->addExtraClass('clearfix')->transform(new FoundationFormTransformation());
		}

		return $form;
	}*/

	public function Form(){
		if($this->URLParams['Action'] === 'completed' || $this->URLParams['Action'] == 'submitted') return;
		$dataFields = singleton('Recipient')->getFrontEndFields()->dataFields();
		
		if($this->CustomLabel) $customLabel = Convert::json2array($this->CustomLabel);

		$fields = array();
		if($this->Fields){
			$fields = explode(",",$this->Fields);
		}

		$recipientInfoSection = new CompositeField();

		$requiredFields = Convert::json2array($this->Required);
		if(!empty($fields)){
			foreach($fields as $field){
				if(isset($dataFields[$field]) && $dataFields[$field]){
					if(is_a($dataFields[$field], "ImageField")){
						if(isset($requiredFields[$field])) {
							$title = $dataFields[$field]->Title()." * ";
						}else{
							$title = $dataFields[$field]->Title();
						}
						$dataFields[$field] = new SimpleImageField(
							$dataFields[$field]->Name(), $title
						);
					}else{
						if(isset($requiredFields[$field])) {
							if(isset($customLabel[$field])){
								$title = $customLabel[$field]." * ";
							} else {
								$title = $dataFields[$field]->Title(). " * ";
							}
						}else{
							if(isset($customLabel[$field])){
								$title = $customLabel[$field];
							} else {
								$title = $dataFields[$field]->Title();
							}
						}
						$dataFields[$field]->setTitle($title);
					}
					$recipientInfoSection->push($dataFields[$field]);
				}
			}
		}
		$formFields = new FieldList(
			new LiteralField("CustomisedHeading", "<h3>" . $this->owner->CustomisedHeading . "</h3>"),
			$recipientInfoSection
		);
		$recipientInfoSection->setID("MemberInfoSection");

		if($this->MailingLists){
			$mailinglists = DataObject::get("MailingList", "ID IN (".$this->MailingLists.")");
		}
		
		if(isset($mailinglists) && $mailinglists && $mailinglists->count()>1){
			$newsletterSection = new CompositeField(
				new LabelField("Newsletters", _t("SubscriptionPage.To", "Subscribe to:"), 4),
				new CheckboxSetField("NewsletterSelection","", $mailinglists, $mailinglists->getIDList())
			);
			$formFields->push($newsletterSection);
		}
		
		$buttonTitle = $this->SubmissionButtonText;
		$actions = new FieldList(
			new FormAction('doSubscribe', $buttonTitle)
		);
		
		if(!empty($requiredFields)) $required = new RequiredFields($requiredFields);
		else $required = null;
		$form = new Form($this, "Form", $formFields, $actions, $required);
		
		// using jQuery to customise the validation of the form
		$FormName = $form->FormName();
		$validationMessage = Convert::json2array($this->ValidationMessage);

		if(!empty($requiredFields)){
			$jsonRuleArray = array();
			$jsonMessageArray = array();
			foreach($requiredFields as $field => $true){
				if($true){
					if(isset($validationMessage[$field]) && $validationMessage[$field]) {
						$error = $validationMessage[$field];
					}else{
						$label=isset($customLabel[$field])?$customLabel[$field]:$dataFields[$field]->Title();
						$error = sprintf(
							_t('SubscriptionPage.PleaseEnter', "Please enter your %s field"),
							$label
						);
					}
					
					if($field === 'Email') {
						$jsonRuleArray[] = $field.":{required: true, email: true}";
						$emailAddrMsg = _t('SubscriptionPage.ValidEmail', 'Please enter your email address');
						$message = <<<JSON
{
required: "$error",
email: "$emailAddrMsg"
}
JSON;
						$jsonMessageArray[] = $field.":$message";
					} else {
						$jsonRuleArray[] = $field.":{required: true}";
						$message = <<<HTML
$error
HTML;
						$jsonMessageArray[] = $field.":\"$message\"";
					}
				}
			}
			$rules = "{".implode(", ", $jsonRuleArray)."}";
			$messages = "{".implode(",", $jsonMessageArray)."}";
		}else{
			$rules = "{Email:{required: true, email: true}}";
			$emailAddrMsg = _t('SubscriptionPage.ValidEmail', 'Please enter your email address');
			$messages = <<<JS
{Email: {
required: "$emailAddrMsg",
email: "$emailAddrMsg"
}}
JS;
		}

		// load the jquery
		// Requirements::javascript(FRAMEWORK_DIR .'/thirdparty/jquery/jquery.js');
		Requirements::javascript('userforms/thirdparty/jquery-validate/jquery.validate.js');
		Requirements::add_i18n_javascript('userforms/javascript/lang');
		// Requirements::javascript('userforms/javascript/UserForm_frontend.js');
		Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');
		// Requirements::javascript('mysite/javascript/jquery.validate.lang.js');
		// set the custom script for this form
		Requirements::customScript(<<<JS
(function($) {
	jQuery(document).ready(function() {
		$("#$FormName").validate({
			ignore: ':hidden',
			errorClass: "required",	
			errorPlacement: function(error, element){
				error.insertAfter(element);
			},
			focusCleanup: true,
			messages: $messages,
			rules: $rules,
			onfocusout : function(element) { this.element(element); }
		});
		$("#$FormName label.left").each(function(){
			$("#"+$(this).attr("for")).attr("placeholder",$(this).text());$(this).remove();
		});
		Placeholders.init();
	});
})(jQuery);
JS
		);

		$form->Actions()->fieldByName('action_doSubscribe')->addExtraClass('right');
		$form->addExtraClass('clearfix')->transform(new FoundationFormTransformation());
		return $form;
	}
}