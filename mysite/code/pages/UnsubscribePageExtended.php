<?php

/**
 * Page type for creating a page that contains a form that visitors can
 * use to unsubscribe from all mailing lists.
 *
 * @package newsletter
 */

class UnsubscriptionPageExtended extends UnsubscriptionPage {

}

class UnsubscriptionPageExtended_Controller extends UnsubscriptionPage_Controller {

	function Form() {
		// Debug::log($this->getAction());
		// $this->Action())->dataFieldByName('action_sendLink')->addExtraClass('button');
		return $this->renderWith('UnsubscribeRequestForm');
		// return $this->Actions->transform(new FoundationFormTransformation());
	}
}