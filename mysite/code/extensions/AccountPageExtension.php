<?php
class AccountPageExtension extends Extension {

	public static function absoluteURL($url){
		if($url != "themes/lilimel/img/pdf/pdf_header.jpg" && $url != "mailto:contact@lilimel.fr"){
			$url = Director::absoluteURL($url);
		}
		return $url; 
	}

	public function updatePDFStream(SS_DOMPDF &$pdf){
		
		$request = $this->owner->getRequest();
		
		$member = Customer::currentUser();

		if($member){
			$customer = "<ul style='list-style:none;padding-left:0'>"
						. "<li><strong>" . _t('AccountPageExtension.CUSTOMER_NAME', 'Customer name: ') . "</strong>" . $member->Name . "</li>"
						. "<li><strong>" . _t('AccountPageExtension.CUSTOMER_EMAIL', 'Customer email: ') . "</strong>" . $member->Email . "</li>"
						."</ul>";

			$content = new HTMLText();

			if ($orderID = $request->param('OtherID')) { // order page

				$order = Order::get()->where("\"Order\".\"ID\" = " . Convert::raw2sql($orderID));
				
				if($order->First()){
					$order = $order->First();

					$title = _t('AccountPageExtension.ORDER', 'Order {title}', array('title' => $order->Title));
					$this->owner->Title = $title;

					$html =  HTTP::urlRewriter($this->owner->customise(array(
							'Order' => $order,
							'Title' => $title))
						->renderWith('PDFAccountPage_order'), function($url){ 
							return AccountPageExtension::absoluteURL($url);
						});

					$content->setValue($html);

				}else{
					die();
				}

			}else{ // all orders

				$orders = Order::get()->where("MemberID = " . Convert::raw2sql($member->ID))->sort('Created DESC');

				
				$html =  HTTP::urlRewriter($this->owner->customise(array(
						'Orders' => $orders,
						'Title' => _t('AccountPageExtension.HISTORY', 'Your Order History')
					))->renderWith('PDFAccountPage'), function($url){
						return AccountPageExtension::absoluteURL($url);
					});

				$content->setValue($html);
			}

			$pdf->setHTML($content);
		}
	}

	public function updateStreamLink(Varchar &$streamLink) {
		$member = Customer::currentUser();

		if($member){
			$request = $this->owner->getRequest();
			if ($request->param('ID')) { // order page
				$streamLink = Controller::join_links($streamLink, $request->param('Action'), $request->param('ID'));
	
			}
		}else {
			$streamLink = false;
		}
	}
}