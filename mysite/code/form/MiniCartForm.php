<?php
class MiniCartForm extends CartForm {
	
	/**
	 * The current {@link Order} (cart).
	 * 
	 * @var Order
	 */
	public $order;
	
	/**
	 * Construct the form, set the current order and the template to be used for rendering.
	 * 
	 * @param Controller $controller
	 * @param String $name
	 * @param FieldList $fields
	 * @param FieldList $actions
	 * @param Validator $validator
	 * @param Order $currentOrder
	 */
	function __construct($controller, $name) {

		parent::__construct($controller, $name, FieldList::create(), FieldList::create(), null);

		$this->setTemplate('MiniCartForm');
	}

}
