<?php
class CataloguePage extends Page {

	public static $has_one = array(
		'MenuImage' => 'Image'
	);

	private static $allowed_children = array(
		'Product',
	);

	public function getSettingsFields(){
		$fields = parent::getSettingsFields();

		$fields->addFieldToTab('Root.Settings',  $imgUf = new UploadField('MenuImage', 'Menu Image'));
		$imgUf->setFolderName('Uploads/catalogue/links');
		$imgUf->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));

		return $fields;
	}

}

class CataloguePage_Controller extends Page_Controller {

	private static $allowed_actions = array(
	);

	public function BuyableChildren(){
		return Product::get()->filter(array('ParentID' => $this->ID, 'Price:GreaterThan' => 0/*, 'Stock:GreaterThan' => 0*/));
	}
}