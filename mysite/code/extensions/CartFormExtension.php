<?php
class CartFormExtension extends Extension {

	public function updateFields(FieldList $fields) {
		$items = $this->owner->order->Items();
		if ($items) foreach ($items as $item) {
			$name = 'Quantity['.$item->ID.']';
			$fields->removeByName($name);

			$fields->push(CartForm_QuantityFieldExtended::create(
				$name, 
				$item->Quantity,
				$item
			));
		}

		$fields->transform(new FoundationFormTransformation());
	}


	public function updateActions(FieldList $actions) {
		$actions->removeByName('action_updateCart');
		$actions->removeByName('action_goToCheckout');

		$actions->push(CompositeField::create(
			FormAction::create('updateCart', _t('CartForm.UPDATE_CART', 'Update Cart'))->addExtraClass('left button'),
			FormAction::create('goToCheckout', _t('CartForm.GO_TO_CHECKOUT', 'Go To Checkout'))->addExtraClass('right button')
		)->addExtraClass('clearfix'));
		
		$actions->transform(new FoundationFormTransformation());
	}

	public function createValidator(RequiredFields $validator) {
		$validator->transform(new FoundationFormTransformation());
	}
}

class CartForm_QuantityFieldExtended extends CartForm_QuantityField {
	/**
	 * Validate this field, check that the current {@link Item} is in the current 
	 * {@Link Order} and is valid for adding to the cart.
	 * 
	 * @see FormField::validate()
	 * @return Boolean
	 */
	function validate($validator) {

		$valid = parent::validate($validator);
		$quantity = $this->Value();

		if($valid && $quantity != 0){
			$stock = Product::get()->filter('ID', $this->Item()->ProductID)->first()->Stock;

			if($stock == 0){
				$errorMessage = _t('Product.ss.NOSTOCK', "This product is not in stock. Please contact us for more detail." );
				$this->setValue(0);

				$validator->validationError(
					$this->getName(),
					$errorMessage,
					"error"
				);
				$valid = false;
			}else if($quantity > $stock) {  // request quantity unavailable
				$errorMessage = _t('CartFormExtension.ITEM_QUANTITY_OVER_STOCK', 'Not enough stock. You can order only {quantity} items', array('quantity' => $stock));
				$this->setValue($this->Item()->Quantity);

				$validator->validationError(
					$this->getName(),
					$errorMessage,
					"error"
				);
				$valid = false;
			}
		}

		return $valid;

	}
}