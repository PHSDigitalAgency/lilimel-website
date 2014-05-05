<?php

class SideReport_EmptyMetaDescription extends SS_Report {
	public function title() {
		return _t('SideReport.EMPTYMETADESCRIPTION',"Pages without meta-description");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("SiteTree", "\"ClassName\" != 'RedirectorPage' AND (\"MetaDescription\" = '' OR \"MetaDescription\" IS NULL)", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			)
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}

class SideReport_OutOfStockProduct extends SS_Report {
	public function title() {
		return _t('SideReport.OUTOFSTOCK',"Out of stock products");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("Product", "\"Stock\" = 0", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			),
			"Price" => "Price",
			"Stock" => "Stock",
			"isNewProduct" => "New?",
			"isFeaturedProduct" => "Featured?",
			"isSaleProduct" => "Sale?",
			"FullPrice" => "Full price",
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}

class SideReport_NoDefinedPriceProduct extends SS_Report {
	public function title() {
		return _t('SideReport.NODEFINEDPRICE',"Products with no defined price");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("Product", "\"Price\" = 0", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			),
			"Price" => "Price",
			"Stock" => "Stock",
			"isNewProduct" => "New?",
			"isFeaturedProduct" => "Featured?",
			"isSaleProduct" => "Sale?",
			"FullPrice" => "Full price",
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}

class SideReport_NewProduct extends SS_Report {
	public function title() {
		return _t('SideReport.NEWPRODUCTS',"New products");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("Product", "\"New\" = 1", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			),
			"Price" => "Price",
			"Stock" => "Stock",
			"isNewProduct" => "New?",
			"isFeaturedProduct" => "Featured?",
			"isSaleProduct" => "Sale?",
			"FullPrice" => "Full price",
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}

class SideReport_SaleProduct extends SS_Report {
	public function title() {
		return _t('SideReport.SALEPRODUCTS',"Sale products");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("Product", "\"Sale\" = 1", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			),
			"Price" => "Price",
			"Stock" => "Stock",
			"isNewProduct" => "New?",
			"isFeaturedProduct" => "Featured?",
			"isSaleProduct" => "Sale?",
			"FullPrice" => "Full price",
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}

class SideReport_FeaturedProduct extends SS_Report {
	public function title() {
		return _t('SideReport.FEATUREDPRODUCTS',"Featured products");
	}

	public function group() {
		return _t('SideReport.ContentGroupTitle', "Content reports");
	}
	public function sort() {
		return 100;
	}
	public function sourceRecords($params = null) {
		return DataObject::get("Product", "\"FeaturedProduct\" = 1", '"Title"');
	}
	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			),
			"Price" => "Price",
			"Stock" => "Stock",
			"isNewProduct" => "New?",
			"isFeaturedProduct" => "Featured?",
			"isSaleProduct" => "Sale?",
			"FullPrice" => "Full price",
		);
	}
	public function getLink($action = null) {
		return Controller::join_links(
			'admin/shop-reports/',
			"$this->class",
			'/', // trailing slash needed if $action is null!
			"$action"
		);
	}
}