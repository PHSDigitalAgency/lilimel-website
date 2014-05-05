<?php
class BlogTree_ControllerExtension extends DataExtension {
	public function SelectedNiceDateLang(){
		$date = $this->owner->SelectedDate();
		
		if(strpos($date, '-')) {
			$date = explode("-",$date);
			$date = date("F", mktime(0, 0, 0, $date[1], 1, date('Y'))). " " .date("Y", mktime(0, 0, 0, date('m'), 1, $date[0]));
		
		} else {
			$date = date("Y", mktime(0, 0, 0, date('m'), 1, $date));
		}

		$formatDate = new Date();
		$formatDate->setValue($date);
		
		return $formatDate->FormatI18N("%B %Y");
	}
}

