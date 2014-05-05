<?php
class OrderFormExtension extends Extension {

	public function updateFields(FieldList $fields) {
		
	
		if($fields->fieldByName('PersonalDetails')){
			$link = $this->owner->controller->Link();
			$note = _t('OrderFormExtension.NOTE','NOTE:');
			$passwd = _t('OrderFormExtension.PLEASE_CHOOSE_PASSWORD','Please choose a password, so you can login and check your order history in the future.');
			$mber = sprintf(
				_t('OrderFormExtension.ALREADY_MEMBER', 'If you are already a member please %s log in. %s'), 
				"<a href=\"" . Director::baseUrl() . "Security/login?BackURL=$link\"><strong>", 
				'</strong></a>'
			);
			
			$fields->removeByName('AccountInfo');
		
			$fields->dataFieldByName('Password')->addExtraClass('row');
			$fields->dataFieldByName('Password')->children->fieldByName('Password[_Password]')->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')->setAttribute('required', 'required')->setAttribute('aria-required', 'true');
			$fields->dataFieldByName('Password')->children->fieldByName('Password[_ConfirmPassword]')->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')->setAttribute('required', 'required')->setAttribute('aria-required', 'true');
		
			$fields->fieldByName('PersonalDetails')->insertBefore(new LiteralField(
				'AccountInfo', 
				"<div class=\"clearfix infoCustomer\"><span class=\"left label alert radius\"><strong>$note</strong> $passwd</span>
				<span class=\"right radius secondary label\">$mber</span></div>"
			), 'Email');
		}

		/**
		 * Shipping address
		 */
		$ShippingFirstName = $fields->dataFieldByName('ShippingFirstName');
		$ShippingSurname = $fields->dataFieldByName('ShippingSurname');
		$ShippingCompany = $fields->dataFieldByName('ShippingCompany');
		$ShippingAddress = $fields->fieldByName('ShippingAddress')->fieldByName('ShippingAddress');
		$ShippingAddressLine2 = $fields->dataFieldByName('ShippingAddressLine2');
		$ShippingCity = $fields->dataFieldByName('ShippingCity');
		$ShippingPostalCode = $fields->dataFieldByName('ShippingPostalCode');
		// $ShippingState = $fields->dataFieldByName('ShippingState');
		$ShippingCountryCode = $fields->dataFieldByName('ShippingCountryCode');

		$fields->removeByName('ShippingFirstName');
		$fields->removeByName('ShippingSurname');
		$fields->removeByName('ShippingCompany');
		$fields->fieldByName('ShippingAddress')->removeByName('ShippingAddress');
		$fields->removeByName('ShippingAddressLine2');
		$fields->removeByName('ShippingCity');
		$fields->removeByName('ShippingPostalCode');
		$fields->removeByName('ShippingState');
		$fields->removeByName('ShippingCountryCode');

		// personal infos
		$fields->fieldByName('ShippingAddress')->push(CompositeField::create(
			$ShippingFirstName
				->addExtraClass('small-10 medium-4 columns')->setAttribute('class', ''),
			$ShippingSurname
				->addExtraClass('small-10 medium-3 columns')->setAttribute('class', ''),
			$ShippingCompany
				->addExtraClass('small-10 medium-3 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		// address lines
		$fields->fieldByName('ShippingAddress')->push(CompositeField::create(
			$ShippingAddress
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$ShippingAddressLine2
				->setTitle(_t('OrderFormExtension.ADDRESS2',"Address 2"))
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		// city, zipcode, country
		$fields->fieldByName('ShippingAddress')->push(CompositeField::create(
			$ShippingCity
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$ShippingPostalCode
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			// $ShippingState
			// 	->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$ShippingCountryCode
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		/**
		 * Billing address
		 */
		$BillingFirstName = $fields->dataFieldByName('BillingFirstName');
		$BillingSurname = $fields->dataFieldByName('BillingSurname');
		$BillingCompany = $fields->dataFieldByName('BillingCompany');
		$BillingAddress = $fields->fieldByName('BillingAddress')->fieldByName('BillingAddress');
		$BillingAddressLine2 = $fields->dataFieldByName('BillingAddressLine2');
		$BillingCity = $fields->dataFieldByName('BillingCity');
		$BillingPostalCode = $fields->dataFieldByName('BillingPostalCode');
		// $BillingState = $fields->dataFieldByName('BillingState');
		$BillingCountryCode = $fields->dataFieldByName('BillingCountryCode');

		$fields->removeByName('BillingFirstName');
		$fields->removeByName('BillingSurname');
		$fields->removeByName('BillingCompany');
		$fields->fieldByName('BillingAddress')->removeByName('BillingAddress');
		$fields->removeByName('BillingAddressLine2');
		$fields->removeByName('BillingCity');
		$fields->removeByName('BillingPostalCode');
		$fields->removeByName('BillingState');
		$fields->removeByName('BillingCountryCode');

		$fields->fieldByName('BillingAddress')->push(CompositeField::create(
			$BillingFirstName
				->addExtraClass('small-10 medium-4 columns')->setAttribute('class', ''),
			$BillingSurname
				->addExtraClass('small-10 medium-3 columns')->setAttribute('class', ''),
			$BillingCompany
				->addExtraClass('small-10 medium-3 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		// address lines
		$fields->fieldByName('BillingAddress')->push(CompositeField::create(
			$BillingAddress
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$BillingAddressLine2
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		// city, zipcode, country
		$fields->fieldByName('BillingAddress')->push(CompositeField::create(
			$BillingCity
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$BillingPostalCode
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			// $BillingState
			// 	->addExtraClass('small-10 medium-5 columns')->setAttribute('class', ''),
			$BillingCountryCode
				->addExtraClass('small-10 medium-5 columns')->setAttribute('class', '')
		)->addExtraClass('row'));

		//Payment fields
		$fields->removeByName('PaymentFields');

		$supported_methods = PaymentProcessor::get_supported_methods();

		$source = array();
		foreach ($supported_methods as $methodName) {
			$methodConfig = PaymentFactory::get_factory_config($methodName);
			$source[$methodName] = $methodConfig['title'];
		}

		$paymentFields = CompositeField::create(
			new HeaderField(_t('OrderFormExtension.PAYMENT',"Payment"), 3),
			OptionsetField::create(
				'PaymentMethod',
				_t('OrderFormExtension.SELECT_PAYMENT_METHOD','Select Payment Method'),
				$source
			)
			 ->setCustomValidationMessage(_t('OrderFormExtension.PLEASE_SELECT_PAYMENT_METHOD',"Please select a payment method."))
			 ->addExtraClass('no-bullet')
		)->setName('PaymentFields');

		$fields->push($paymentFields);

		// load the jquery
		// Requirements::javascript(FRAMEWORK_DIR .'/thirdparty/jquery-validate/jquery.validate.js');

		// Requirements::javascript('mysite/javascript/PaymentMethodSelection.js');

		Requirements::javascript('userforms/thirdparty/jquery-validate/jquery.validate.js');
		Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');
		Requirements::customScript(<<<JS
(function($) {
	jQuery(document).ready(function() {
		var orderForm = $("#OrderForm_OrderForm").validate({
			ignore: ':hidden',
			errorClass: "required",	
			errorPlacement: function(error, element){
				if(element.is(":radio")) {
					error.insertAfter(element.closest("ul"));
				} else {
					error.insertAfter(element);
				}			},
			focusCleanup: true,
			messages: { PaymentMethod:"Veuillez sélectionner une méthode de paiement." },
			rules: {
				PaymentMethod:{required:true},
				ShippingPostalCode:{required:true, maxlength:5, minlength:5},
				BillingPostalCode:{required:true, maxlength:5, minlength:5}
			},
			onfocusout : function(element) { this.element(element); }
		});
		$("#OrderForm_OrderForm label.left").each(function(){
			$("#"+$(this).attr("for")).attr("placeholder",$(this).text());$(this).remove();
		});
		Placeholders.init();
		$('.sws .order-form').on('submit', function(e){
			if(!orderForm.valid()){
				e.preventDefault();
				e.stopImmediatePropagation();
			}
		});
	});
})(jQuery);
JS
		);

	}

	public function updateActions(FieldList $fields) {
		$fields->dataFieldByName('action_process')->addExtraClass('button right');
	}

	public function updateValidator($validator) {

		$validator->appendRequiredFields(RequiredFields::create(
			'ShippingFirstName',
			'ShippingSurname',
			'ShippingAddress',
			'ShippingCity',
			'ShippingCountryCode',
			'ShippingPostalCode',
			'BillingFirstName',
			'BillingSurname',
			'BillingAddress',
			'BillingCity',
			'BillingCountryCode',
			'BillingPostalCode'
		));
	}
}