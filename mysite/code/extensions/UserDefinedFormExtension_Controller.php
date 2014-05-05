<?php
class UserDefinedFormExtension_Controller extends DataExtension {

	public function updateForm(Form $form)
	{
		$form->addExtraClass('clearfix');
		$form->Actions()->fieldByName('action_process')->addExtraClass('right');
	}

}