<?php
class ProductFormExtension extends Extension {

	public function updateFields($fields) {
		$quantity = $fields->fieldByName('Quantity')->value();
		$fields->removeByName('Quantity');
		
		if(ProductExtension::getMaxOrderItem(Controller::curr()->ID) > 0) {
			$quantityField = ProductForm_QuantityFieldExtended::create('Quantity', _t('ProductFormExtension.QUANTITY', 'Quantity'), $quantity)->setTitle('')->addExtraClass('small-2 small-pull-3 columns')->setAttribute('class', 'radius');

			$group = FieldGroup::create(
				LiteralField::create('PreFixQtity', '<div class="small-2 small-push-3 columns"><span class="prefix radius"><strong>' . _t('ProductFormExtension.QUANTITY', 'Quantity') . '</strong></span></div>'),
				$quantityField
			)->addExtraClass('row collapse');

			$fields->push($group);
		}else {
				$fields->push(
					LiteralField::create('Nostock',
						'<p class="label alert radius">'  . _t('Product.ss.NOSTOCK',
								"This product is not in stock. Please <a href='{url}' title='Contact Us'>contact us</a> for more detail.",
								array( 'url' => SiteTree::get()->filter('URLSegment','contact')->First()->Link() )
						) . '</p>'));
		}

		$fields->transform(new FoundationFormTransformation());
	}
	
	public function updateActions($fields) {
		
		$actionAdd = $fields->fieldByName('action_add')->setTitle(_t('ProductFormExtension.ADD_TO_CART', 'Add to Cart'))->addExtraClass('button');

		$fields->removeByName('action_add');

		if(ProductExtension::getMaxOrderItem(Controller::curr()->ID) > 0) {
			$fields->push(CompositeField::create(
				$actionAdd
			)->addExtraClass('text-center'));
		}

		$fields->transform(new FoundationFormTransformation());
	}

}

class ProductForm_QuantityFieldExtended extends ProductForm_QuantityField {

	/**
	 * Validate the quantity is above 0.
	 * Extension: Validate that quantity added to cart is not above the stock
	 * 
	 * @see FormField::validate()
	 * @return Boolean
	 */
	public function validate($validator) {

		$valid = parent::validate($validator);
		
		if($valid){
			$quantity = $this->Value();
			$maxQtity = ProductExtension::getMaxOrderItem(Controller::curr()->ID);

			if($quantity > $maxQtity) {  // request quantity unavailable
				$errorMessage = _t('ProductFormExtension.ITEM_QUANTITY_OVER_STOCK', 'Not enough stock. You can order only {quantity} items', array('quantity' => $maxQtity));
				$this->setValue($maxQtity);

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