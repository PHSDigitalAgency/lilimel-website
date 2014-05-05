<?php
class RepayFormExtension extends Extension {

	public function updateFields($fields) {

		/*Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-entwine/dist/jquery.entwine-dist.js');
		Requirements::javascript('swipestripe/javascript/OrderForm.js');
		Requirements::javascript('swipestripe-addresses/javascript/Addresses_OrderForm.js');

		$shippingAddressFields = CompositeField::create(
			HeaderField::create(_t('CheckoutPage.SHIPPING_ADDRESS',"Shipping Address"), 3),
			TextField::create('ShippingFirstName', _t('CheckoutPage.FIRSTNAME',"First Name"))
				->addExtraClass('shipping-firstname')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_FIRSTNAME',"Please enter a first name.")),
			TextField::create('ShippingSurname', _t('CheckoutPage.SURNAME',"Surname"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_SURNAME',"Please enter a surname.")),
			TextField::create('ShippingCompany', _t('CheckoutPage.COMPANY',"Company")),
			TextField::create('ShippingAddress', _t('CheckoutPage.ADDRESS',"Address"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_ADDRESS',"Please enter an address."))
				->addExtraClass('address-break'),
			TextField::create('ShippingAddressLine2', '&nbsp;'),
			TextField::create('ShippingCity', _t('CheckoutPage.CITY',"City"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_CITY',"Please enter a city.")),
			TextField::create('ShippingPostalCode', _t('CheckoutPage.POSTAL_CODE',"Zip / Postal Code")),
			TextField::create('ShippingState', _t('CheckoutPage.STATE',"State / Province"))
				->addExtraClass('address-break'),
			DropdownField::create('ShippingCountryCode', 
					_t('CheckoutPage.COUNTRY',"Country"), 
					Country_Shipping::get()->map('Code', 'Title')->toArray()
				)
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_COUNTRY',"Please enter a country."))
				->addExtraClass('country-code')
		)->setID('ShippingAddress')->setName('ShippingAddress');

		$billingAddressFields = CompositeField::create(
			HeaderField::create(_t('CheckoutPage.BILLINGADDRESS',"Billing Address"), 3),
			$checkbox = CheckboxField::create('BillToShippingAddress', _t('CheckoutPage.SAME_ADDRESS',"same as shipping address?"))
				->addExtraClass('shipping-same-address'),
			TextField::create('BillingFirstName', _t('CheckoutPage.FIRSTNAME',"First Name"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURFIRSTNAME',"Please enter your first name."))
				->addExtraClass('address-break'),
			TextField::create('BillingSurname', _t('CheckoutPage.SURNAME',"Surname"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURSURNAME',"Please enter your surname.")),
			TextField::create('BillingCompany', _t('CheckoutPage.COMPANY',"Company")),
			TextField::create('BillingAddress', _t('CheckoutPage.ADDRESS',"Address"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURADDRESS',"Please enter your address."))
				->addExtraClass('address-break'),
			TextField::create('BillingAddressLine2', '&nbsp;'),
			TextField::create('BillingCity', _t('CheckoutPage.CITY',"City"))
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURCITY',"Please enter your city")),
			TextField::create('BillingPostalCode', _t('CheckoutPage.POSTALCODE',"Zip / Postal Code")),
			TextField::create('BillingState', _t('CheckoutPage.STATE',"State / Province"))
				->addExtraClass('address-break'),
			DropdownField::create('BillingCountryCode', 
					_t('CheckoutPage.COUNTRY',"Country"), 
					Country_Billing::get()->map('Code', 'Title')->toArray()
				)->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURCOUNTRY',"Please enter your country."))
		)->setID('BillingAddress')->setName('BillingAddress');

		$fields->push($shippingAddressFields);
		$fields->push($billingAddressFields);*/

		//Payment fields
		$fields->removeByName('PaymentFields');

		$supported_methods = PaymentProcessor::get_supported_methods();

		$source = array();
		foreach ($supported_methods as $methodName) {
			$methodConfig = PaymentFactory::get_factory_config($methodName);
			$source[$methodName] = $methodConfig['title'];
		}

		$paymentFields = CompositeField::create(
			new HeaderField(_t('RepayFormExtension.PAYMENT',"Payment"), 3),
			OptionsetField::create(
				'PaymentMethod',
				_t('RepayFormExtension.SELECT_PAYMENT_METHOD','Select Payment Method'),
				$source
			)->setCustomValidationMessage(_t('RepayFormExtension.PLEASE_SELECT_PAYMENT_METHOD',"Please select a payment method."))->addExtraClass('no-bullet')
		)->setName('PaymentFields');

		$fields->push($paymentFields);

		// Requirements::javascript('mysite/javascript/PaymentMethodSelection.js');
		// Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');

		$fields->transform(new FoundationFormTransformation());


		Requirements::javascript('userforms/thirdparty/jquery-validate/jquery.validate.js');
		Requirements::javascript('userforms/thirdparty/Placeholders.js/Placeholders.min.js');
		Requirements::customScript(<<<JS
(function($) {
	jQuery(document).ready(function() {
		var orderForm = $("#RepayForm_RepayForm").validate({
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
				PaymentMethod:{required:true}
			},
			onfocusout : function(element) { this.element(element); }
		});
		$("#RepayForm_RepayForm label.left").each(function(){
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

	public function updateActions($actions) {
		$process = $actions->fieldByName('action_process')->addExtraClass('right button');

		$actions->removeByName('action_process');

		$actions->push(CompositeField::create($process)->addExtraClass('clearfix'));

		$actions->transform(new FoundationFormTransformation());
	}

	/*public function getShippingAddressFields() {
		return $this->owner->Fields()->fieldByName('ShippingAddress');
	}

	public function getBillingAddressFields() {
		return $this->owner->Fields()->fieldByName('BillingAddress');
	}*/
}