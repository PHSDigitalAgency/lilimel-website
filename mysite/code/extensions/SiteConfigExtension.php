<?php
class SiteConfigExtension extends DataExtension {
	public static $db = array(
		'Emails' => 'MultiValueField',
		// 'Phones' => 'MultiValueField',
		// 'Addresses' => 'MultiValueField',
		'FacebookURL' => 'Varchar(255)',
		'TwitterURL' => 'Varchar(255)',
		'Copyright' => 'Varchar',
	);

	public static $has_one = array(
		'Logo' => 'Image',
	);

	public function updateCMSFields(FieldList $fields){
		
		$fields->addFieldToTab('Root.Main', new MultiValueTextField('Emails', _t('SiteConfigExtension.EMAILS', 'Emails')));
		// $fields->addFieldToTab('Root.Main', new MultiValueTextField('Phones', _t('SiteConfigExtension.Phones', 'Phones')));
		// $fields->addFieldToTab('Root.Main', new MultiValueTextField('Addresses', _t('SiteConfigExtension.Addresses', 'Addresses')));

		$fields->addFieldToTab('Root.Main', 
			UploadField::create('Logo', _t('SiteConfigExtension.LOGO', 'Logo'))
			->setFolderName('Uploads/logo')
		);

		$fields->addFieldToTab('Root.Main', new TextField('FacebookURL', _t('SiteConfigExtension.FACEBOOKURL', 'Facebook URL')));
		$fields->addFieldToTab('Root.Main', new TextField('TwitterURL', _t('SiteConfigExtension.TWITTERURL', 'Twitter URL')));
		$fields->addFieldToTab('Root.Main', new TextField('Copyright', _t('SiteConfigExtension.COPYRIGHT', 'Copyright')));
		
		if(!Member::currentUser()->inGroup('administrators')){
			$fields->removeByName('Title');
			$fields->removeByName('Logo');
			$fields->removeByName('Theme');
			$fields->removeByName('Access');
		}
	}

	public function validate(ValidationResult $validationResult) {
		
		$emails = $this->owner->getField('Emails')->getValues();
		foreach ($emails as $email) {
			if(!Email::validEmailAddress($email)){
				$validationResult->error('One of the email is not valid.');
				break;
			}
		}
	}
}