<?php
class OrderExtension extends DataExtension {

	public function onAfterPayment() {

		// if status is paid we reduce the stock on all item of Product (Stage, Live, Versioned)
		// TODO: find more elegant way than raw SQL to modify all the table without creating versioning
		// Debug::log("Status: " . $this->owner->Status . PHP_EOL . "Payment Status: " . $this->owner->PaymentStatus);

		if($this->owner->PaymentStatus == 'Paid'){
			foreach($this->owner->Items() as $k => $v){
				$product = Product::get()->filter('ID', $v->ProductID)->First();
				$newStock = $product->Stock - $v->Quantity;
				$productID = $v->ProductID;

				DB::query('UPDATE "Product" SET "Stock" = ' . $newStock . ' WHERE "Product"."ID" = ' . $productID);
				DB::query('UPDATE "Product_Live" SET "Stock" = ' . $newStock . ' WHERE "Product_Live"."ID" = ' . $productID);
				DB::query('UPDATE "Product_versions" SET "Stock" = ' . $newStock . ' WHERE "Product_versions"."RecordID" = ' . $productID);
			}

		}
	}
}
/*
class OrderExtension_Update extends DataExtension {

	// public static $api_access = true;

	public function canView($member = false) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-administrators'));
	}

	public function canEdit($member = false) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-administrators'));
	}

	public function canDelete($member = false) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-administrators'));
	}

	public function canCreate($member = false) {
		if(!$member) $member = Member::currentUser();
		return $member->inGroups(array('administrators', 'shop-administrators'));
	}
}*/