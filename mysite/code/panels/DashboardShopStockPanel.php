<?php

class DashboardShopStockPanel extends DashboardPanel {

  public static $db = array (
    'StockLimit' => 'Int'
  );

  public function getLabel() {
    return _t('DashboardShopStockPanel.SHOPSTOCKLABEL','Shop Stock');
  }


  public function getDescription() {
    return _t('DashboardShopStockPanel.SHOPSTOCKDESCRIPTION','Shows products nearly out of stock.');
  }


  public function getConfiguration() {
    $fields = parent::getConfiguration();
    $fields->push(NumericField::create("StockLimit","Stock limit"));
    return $fields;
  }



  public function Products() {
    $products = Product::get()->sort("Stock ASC")->filter(array('Stock:LessThanOrEqual' => $this->StockLimit));
    return $products;
  }
}