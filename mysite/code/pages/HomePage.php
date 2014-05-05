<?php
class HomePage extends Page {

	public static $has_many = array(
		'News' => 'NewsPage',
	);

	private static $allowed_children = array(
		'NewsPage',
		'RedirectorPage',
	);

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Content');

		if(!Member::currentUser()->inGroup('administrators')){
			$fields->removeByName('URLSegment');
		}

		return $fields;
	}
}
class HomePage_Controller extends Page_Controller {

	private static $allowed_actions = array (
	);
}

class HomePage_Images extends DataObject {
    static $db = array(
        'HomePageID' => 'Int',
        'ImageID' => 'Int',
        'Caption' => 'Text',
        'SortOrder' => 'Int',
    );
    public static $has_one = array(
        'LinkToUrl' => 'Link'
    );
}