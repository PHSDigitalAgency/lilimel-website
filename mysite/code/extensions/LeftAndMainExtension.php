<?php

class LeftAndMainExtension extends Extension {

	public function init($dummy) {
		if(!Member::currentUser()->inGroup('administrators')){
			CMSMenu::remove_menu_item('SecurityAdmin');
		}
	}
}