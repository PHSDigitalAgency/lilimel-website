<?php
class BlogEntryExtension extends DataExtension {

    public function DateLang() {
        $date = new Date();
        $date->setValue($this->owner->Date);
        // return $date->FormatI18N("%A %e de %B de %Y");
        return $date->FormatI18N("%e %B %Y");
    }


}

class BlogEntry_Images extends DataObject {
    static $db = array(
        'BlogEntryID' => 'Int',
        'ImageID' => 'Int',
        'Caption' => 'Text',
        'SortOrder' => 'Int',
        'Layout' => 'Enum("Portrait,Landscape")',
        'UseAsThumb' => 'Boolean(0)',
    );
}