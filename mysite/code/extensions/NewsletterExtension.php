<?php

class NewsletterExtension extends DataExtension {
	public static $db = array(
		'Number' => 'Varchar(10)'
	);

	public function updateCMSFields(FieldList $fields){
		$fields->addFieldToTab('Root.Main', TextField::create('Number', _t('NewsletterExtension.NUMBER', 'Number')));
	}
}

class NewsletterEmailExtension extends Extension {
	public function getNewsletterNumberGeneratedHeader(){
		
		$filter = new FileNameFilter();
		$num = $this->owner->newsletter->Number;
		$folder = Folder::find_or_make('newsletter');
		$imgSrc = $filter->filter('header-' . $num . '.png');


		if($folder !== null && !file_exists(Director::getAbsFile('assets/newsletter/' . $imgSrc))){

			require_once Director::getAbsFile('mysite/thirdparty/Box.php');

			$im = imagecreatefromjpeg(Director::getAbsFile('mysite/images/newsletter/img_header_newsletter.jpg'));

			$box = new Box($im);
			$box->setFontFace(Director::getAbsFile('mysite/fonts/Alcefun.otf'));
			$box->setFontColor([77, 77, 77]);
			$box->setFontSize(30);
			$box->setBox(5, 0, 166, 32);
			$box->setTextAlign('left', 'top');
			$box->draw("Newsletter");

			$date = date('j F Y');
			$formatDate = new LocalDate();
			$formatDate->setValue($date);

			$box = new Box($im);
			$box->setFontFace(Director::getAbsFile('mysite/fonts/Alcefun.otf'));
			$box->setFontColor([77, 77, 77]);
			$box->setFontSize(15);
			$box->setBox(5, 37, 166, 21);
			$box->setTextAlign('left', 'top');
			$box->draw($formatDate->Format('j F Y'));
			
			$box = new Box($im);
			$box->setFontFace(Director::getAbsFile('mysite/fonts/Alcefun.otf'));
			$box->setFontColor([41, 171, 226]);
			$box->setFontSize(55);
			$box->setBox(173, -8, 51, 54);
			$box->setTextAlign('center', 'top');
			$box->draw($num);

			imagepng($im, Director::baseFolder() . '/assets/newsletter/' . $imgSrc);
			imagedestroy($im);

			Filesystem::sync($folder->ID, false);
		}

		return "<img src='assets/newsletter/" . $imgSrc . "'/>";
	}
}
