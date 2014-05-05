<?php
class AboutPage extends Page {

}
class AboutPage_Controller extends Page_Controller {

	private static $allowed_actions = array (
	);
}
class AboutPage_Images extends DataObject {
    static $db = array(
        'AboutPageID' => 'Int',
        'ImageID' => 'Int',
        'Caption' => 'Text',
        'SortOrder' => 'Int',
    );
}