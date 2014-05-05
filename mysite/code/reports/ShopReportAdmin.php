<?php

class ShopReportAdmin extends ReportAdmin implements PermissionProvider {
	private static $url_segment = 'shop-reports';
		
	private static $menu_title = 'Shop Reports';	
	
	protected $dataClass = 'Product';

	// protected $reportObject = array('SideReport_OutOfStockProduct');

	public function init() {
		parent::init();

		Requirements::css('mysite/css/ReportAdmin.css');
	}

	public function Reports() {
		$reports = new ArrayList();
		$reports->push(new SideReport_EmptyMetaDescription());
		$reports->push(new SideReport_OutOfStockProduct());
		$reports->push(new SideReport_NoDefinedPriceProduct());
		$reports->push(new SideReport_NewProduct());
		$reports->push(new SideReport_SaleProduct());
		$reports->push(new SideReport_FeaturedProduct());
		return $reports;
	}

	public function providePermissions() {
		$title = _t("ShopReportAdmin.MENUTITLE", LeftAndMain::menu_title_for_class($this->class));
		return array(
			"CMS_ACCESS_ShopReportAdmin" => array(
				'name' => _t('CMSMain.ACCESS', "Access to '{title}' section", array('title' => $title)),
				'category' => _t('Permission.CMS_ACCESS_CATEGORY', 'CMS Access')
			)
		);
	}
}