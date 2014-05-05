<?php
class ShopAdminExtension extends Extension {

	public static $managed_models = array(
		'Product',
		'Order',
		'Customer',
		'ShopConfig'
	);

	// public function updateEditForm(Form $form) {

		// $form
	// }
}