<?php

class Gallery_PageExtension extends DataExtension {

	public static $many_many = array(
		'Images' => 'Image'	
	);
	
	public function updateCMSFields(FieldList $fields) {

		$fields->addFieldToTab('Root.Gallery', GalleryUploadField::create('Images', '', $this->owner->OrderedImages()));
	}
	
	public function OrderedImages() {
		$table = $this->owner->className . '_Images';

		return $this->owner->getManyManyComponents(
			'Images',
			'',
			"\"{$table}\".\"SortOrder\" ASC"
		);
	}

	public function ThumbnailImage() {
		$table = $this->owner->className . '_Images';

		$thumbs = $this->owner->getManyManyComponents(
			'Images',
			'',
			"\"{$table}\".\"UseAsThumb\" DESC, \"{$table}\".\"SortOrder\" ASC"
		);
		
		if(isset($thumbs)){
			return $thumbs->First();
		}
		return false;
		
	}

	public function ThumbnailImages() {
		$table = $this->owner->className . '_Images';

		$thumbs = $this->owner->getManyManyComponents(
			"Images",
			"\"UseAsThumb\" = true",
			"\"{$table}\".\"SortOrder\" ASC"
		);
		
		if(count($thumbs)){
			return $thumbs;
		} else {
			$thumbs = $this->owner->getManyManyComponents(
				'Images',
				'',
				"\"{$table}\".\"UseAsThumb\" DESC, \"{$table}\".\"SortOrder\" ASC"
			);

			if(isset($thumbs)){
				return $thumbs->limit(1);
			}
			return false;
		}
		
	}

	public function Layout() {
		if($this->owner->ThumbnailImage()){
			$id = $this->owner->ThumbnailImage()->ID;

			list($parentClass, $componentClass, $parentField, $componentField, $table) = $this->owner->many_many('Images');

			$img = $table::get()->filter(array("{$parentField}" => $this->owner->ID, "{$componentClass}ID" => $id))->First();
			
			return $img->Layout;
		}
		return false;
	}

}

class Gallery_ImageExtension extends DataExtension {

	public static $belongs_many_many = array(
		'Pages' => 'Page'
	);
	
	public function onBeforeDelete(){
		$table = Controller::Curr()->getEditForm()->getRecord()->ClassName . "_Images";

		if(ClassInfo::hasTable($table)){
			$imgs = $table::get()->filter('ImageID', $this->owner->ID);
			
			if(isset($imgs)) $imgs->first()->delete();
		}
	}


	public function getUploadFields() {

		$fields = $this->owner->getCMSFields();

		$fileAttributes = $fields->fieldByName('Root.Main.FilePreview')->fieldByName('FilePreviewData');

		// Debug::log(Controller::Curr()->getEditForm()->getRecord()->ClassName);

		$table = Controller::Curr()->getEditForm()->getRecord()->ClassName . "_Images";

		if(ClassInfo::hasTable($table)){
			
			$obj = singleton($table);

			if($obj->hasField('Caption'))
				$fileAttributes->push(TextareaField::create('Caption', 'Caption:')->setRows(4));

			if($obj->hasField('UseAsThumb'))
				$fileAttributes->push(CheckboxField::create('UseAsThumb', 'Use as thumbnail?'));

			if($obj->hasField('Layout'))
				$fileAttributes->push(DropdownField::create('Layout', 'Layout', $obj->dbObject('Layout')->enumValues()));
			
			if($obj->hasField('LinkToUrlID'))
				$fileAttributes->push(new LinkField('LinkToUrlID', 'Link to page or file'));

		}else {
			// TODO: throw a validation error
		}

		$fields->removeFieldsFromTab('Root.Main', array(
			'Title',
			'Name',
			'OwnerID',
			'ParentID',
			'Created',
			'LastEdited',
			'BackLinkCount',
			'Dimensions'
		));

		return $fields;
	}

	public function getImageObject() {
		$page = Controller::curr()->data();
		list($parentClass, $componentClass, $parentField, $componentField, $table) = $page->many_many('Images');
		
		return $table::get()
			->where("\"{$parentField}\" = '{$page->ID}' AND \"ImageID\" = '{$this->owner->ID}'")
			->first();
	}

	public function Caption() {
		/*$page = Controller::curr()->data();
		list($parentClass, $componentClass, $parentField, $componentField, $table) = $page->many_many('Images');
		
		$joinObj = $table::get()
			->where("\"{$parentField}\" = '{$page->ID}' AND \"ImageID\" = '{$this->owner->ID}'")
			->first();*/

		return $this->owner->getImageObject()->Caption;
	}

	public function LinkToUrl() {
		/*$page = Controller::curr()->data();
		list($parentClass, $componentClass, $parentField, $componentField, $table) = $page->many_many('Images');

		$joinObj = $table::get()
			->where("\"{$parentField}\" = '{$page->ID}' AND \"ImageID\" = '{$this->owner->ID}'")
			->first();

		return Link::get()->where("\"ID\" = '{$joinObj->LinkToUrlID}'")->first();*/

		$linkToUrlID = $this->owner->getImageObject()->LinkToUrlID;
		return Link::get()->where("\"ID\" = '{$linkToUrlID}'")->first();
	}

	public function Link() {
		return $this->owner->LinkToUrl();
	}

}