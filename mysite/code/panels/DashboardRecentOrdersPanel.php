<?php

class DashboardRecentOrdersPanel extends DashboardPanel {

  static $db = array (
    'Count' => 'Int',
    'ShowProcessing' => 'Boolean',
    'ShowPending' => 'Boolean',
    'ShowCart' => 'Boolean',
    'ShowDispatched' => 'Boolean',
    'ShowCancelled' => 'Boolean',
    'ShowPaid' => 'Boolean',
    'ShowUnpaid' => 'Boolean',
  );

  public function getLabel() {
    return _t('DashboardRecentOrdersPanel.RECENTORDERS','Recent Orders');
  }


  public function getDescription() {
    return _t('DashboardRecentOrdersPanel.RECENTORDERSDESCRIPTION','Shows recent orders.');
  }


  public function getConfiguration() {
    $fields = parent::getConfiguration();
    $fields->push(TextField::create("Count", "Number of orders to show"));
    $fields->push(CheckboxField::create("ShowProcessing","Show processing orders"));
    $fields->push(CheckboxField::create("ShowPending","Show pending orders"));
    $fields->push(CheckboxField::create("ShowCart","Show cart orders"));
    $fields->push(CheckboxField::create("ShowDispatched","Show dispatched orders"));
    $fields->push(CheckboxField::create("ShowCancelled","Show cancelled orders"));
    $fields->push(CheckboxField::create("ShowPaid","Show paid orders"));
    $fields->push(CheckboxField::create("ShowUnpaid","Show unpaid orders"));
    return $fields;
  }



  public function Orders() {
    $status = array();

    if($this->ShowProcessing) $status[] = 'Processing';
    if($this->ShowPending) $status[] = 'Pending';
    if($this->ShowCart) $status[] = 'Cart';
    if($this->ShowDispatched) $status[] = 'Dispatched';
    if($this->ShowCancelled) $status[] = 'Cancelled';

    $paymentStatus = array();

    if($this->ShowPaid) $paymentStatus[] = 'Paid';
    if($this->ShowUnpaid) $paymentStatus[] = 'Unpaid';

    $orders = Order::get()->sort("Created DESC")->filterAny(array(
        'Status' => $status,
        'PaymentStatus' => $paymentStatus,
      ))->limit($this->Count);

    return $orders;
  }

  /*public function Chart() {
    $chart = DashboardChart::create("Order history, last 30 days", "Ordered On", "Number of orders");
    $result = DB::query("SELECT COUNT(*) AS OrdersCount, DATE_FORMAT(OrderedOn,'%d %b %Y') AS Date FROM \"Order\" GROUP BY OrderedOn");
    if($result) {
      while($row = $result->nextRecord()) {
        $chart->addData($row['Date'], $row['OrdersCount']);
      }
    }
    return $chart;
  }*/
}