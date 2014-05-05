<?php
class ProductExtension extends DataExtension {
    public static $db = array(
        'Sale' => 'Boolean(0)',
        'New' => 'Boolean(1)',
        'FeaturedProduct' => 'Boolean(0)',
        'PriceWithoutReduction' => 'Decimal(19,4)',
        'Stock' => 'Int',
        'TitleTab1' => 'Varchar',
        'TitleTab2' => 'Varchar',
        'TitleTab3' => 'Varchar',
        'TitleTab4' => 'Varchar',
        'ContentTab1' => 'HTMLText',
        'ContentTab2' => 'HTMLText',
        'ContentTab3' => 'HTMLText',
        'ContentTab4' => 'HTMLText',
    );

    /*public static $default = array(
        'TitleTab1' => 'Description',
        'TitleTab2' => 'Advantage',
        'TitleTab3' => 'Weight and Dimensions',
        'TitleTab4' => 'Advice',
    );*/

    public static $summary_fields = array(
        'Amount.Nice' => 'Price',
        'Title' => 'Title',
        'Stock' => 'Stock',
    );


    public static $searchable_fields = array(
        'Title' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
            'title' => 'Name'
        ),
        'Price' => array(
            'field' => 'NumericField',
            'filter' => 'ExactMatchFilter',
            'title' => 'Price'
        ),
        'Stock' => array(
            'field' => 'NumericField',
            'filter' => 'ExactMatchFilter',
            'title' => 'Stock'
        ),
    );


    public function populateDefaults(){
        if(!isset($this->owner->TitleTab1) || $this->owner->TitleTab1 === null) {
            $this->owner->TitleTab1 = _t('ProductExtension.DESCRIPTION', 'Description');
        }
        if(!isset($this->owner->TitleTab2) || $this->owner->TitleTab2 === null) {
            $this->owner->TitleTab2 = _t('ProductExtension.ADVANTAGE', 'Advantage');
        }
        if(!isset($this->owner->TitleTab3) || $this->owner->TitleTab3 === null) {
            $this->owner->TitleTab3 = _t('ProductExtension.WEIGHT_AND_DIMENSIONS', 'Weight and Dimensions');
        }
        if(!isset($this->owner->TitleTab4) || $this->owner->TitleTab4 === null) {
            $this->owner->TitleTab4 = _t('ProductExtension.ADVICE', 'Advice');
        }
    }

    public function isNewProduct(){
        return $this->owner->New ? _t("ProductExtension.YES", "Yes") : _t("ProductExtension.NO", "No");
    }

    public function isSaleProduct(){
        return $this->owner->Sale ? _t("ProductExtension.YES", "Yes") : _t("ProductExtension.NO", "No");
    }

    public function isFeaturedProduct(){
        return $this->owner->FeaturedProduct ? _t("ProductExtension.YES", "Yes") : _t("ProductExtension.NO", "No");
    }
    /*
    public function onAfterInit() {

    }*/

    public function FullPrice() {

        // TODO: Multi currency
        $shopConfig = ShopConfig::current_shop_config();

        $amount = new Price();
        $amount->setAmount($this->owner->PriceWithoutReduction);
        $amount->setCurrency($shopConfig->BaseCurrency);
        $amount->setSymbol($shopConfig->BaseCurrencySymbol);

        return $amount;
    }

    public function updateProductCMSFields(FieldList $fields) {
        // $content = $fields->dataFieldByName('Content');
        // $content->setTitle(_t('ProductExtension.DESCRIPTION', 'Description'));

        $fields->removeByName('Attributes');
        // $fields->removeByName('Content');

        $fields->insertAfter(TextField::create('Stock', _t('ProductExtension.STOCK', 'Stock')), 'Price');
        $fields->insertAfter(CheckboxField::create('FeaturedProduct', _t('ProductExtension.FeaturedProduct', 'Featured product ?')), 'Stock');
        $fields->insertAfter(CheckboxField::create('New', _t('ProductExtension.NEW', 'New')), 'FeaturedProduct');
        $fields->insertAfter(CheckboxField::create('Sale', _t('ProductExtension.SALE', 'Sale')), 'New');
        
        $fields->insertAfter(PriceField::create('PriceWithoutReduction', _t('ProductExtension.PRICEWITHOUTREDUCTION', 'Price without reduction')), 'Sale');

        $fields->addFieldToTab('Root.Tab1', TextField::create('TitleTab1', _t('ProductExtension.TITLE', 'Title')));
        $fields->addFieldToTab('Root.Tab2', TextField::create('TitleTab2', _t('ProductExtension.TITLE', 'Title')));
        $fields->addFieldToTab('Root.Tab3', TextField::create('TitleTab3', _t('ProductExtension.TITLE', 'Title')));
        $fields->addFieldToTab('Root.Tab4', TextField::create('TitleTab4', _t('ProductExtension.TITLE', 'Title')));

        $fields->addFieldToTab('Root.Tab1', HtmlEditorField::create('ContentTab1', _t('ProductExtension.CONTENT', 'Content')));
        $fields->addFieldToTab('Root.Tab2', HtmlEditorField::create('ContentTab2', _t('ProductExtension.CONTENT', 'Content')));
        $fields->addFieldToTab('Root.Tab3', HtmlEditorField::create('ContentTab3', _t('ProductExtension.CONTENT', 'Content')));
        $fields->addFieldToTab('Root.Tab4', HtmlEditorField::create('ContentTab4', _t('ProductExtension.CONTENT', 'Content')));
        
    }

    /**
     * Return the max product quantity that can be added to the cart
     * depend on the stock and the number of item already in the cart
     * return: true if available, false instead
     *
     */
    public static function getMaxOrderItem($productID){
        $product = Product::get()->filter('ID', $productID)->first(); // Controller::curr();

        // $productID = $product->ID;
        $stock = $product->Stock;
        $cart = Cart::get_current_order();
        $quantity = 0;

        // loop the items in cart
        foreach($cart->Items() as $k => $v){
            $product = $v->Product();

            // current item already in cart
            if($product->ID == $productID){
                $quantity = $v->Quantity;
            }
        }
        return $stock - $quantity;
    }

    public function getPrevProductLink() {
        $parentID = $this->owner->ParentID;

        $prevProduct = Product::get()->filter(array('ParentID' => $parentID, 'Sort:LessThan' => $this->owner->Sort))->Sort('Sort DESC')->limit(1);

        if(count($prevProduct)){
            return $prevProduct->First()->Link();

        }else{
            $parentPage = CataloguePage::get()->byID($parentID);
            $prevCatalogue = CataloguePage::get()->filter(array('Sort:LessThan' => $parentPage->Sort))->Sort('Sort DESC')->limit(1);

            if(count($prevCatalogue)) $prevCatalogueID = $prevCatalogue->First()->ID;
            else $prevCatalogueID = CataloguePage::get()->sort('Sort DESC')->First()->ID;

            return Product::get()->filter('ParentID', $prevCatalogueID)->Sort('Sort DESC')->First()->Link();
        }
    }

    public function getNextProductLink() {
        $parentID = $this->owner->ParentID;

        $nextProduct = Product::get()->filter(array('ParentID' => $parentID, 'Sort:GreaterThan' => $this->owner->Sort))->Sort('Sort ASC')->limit(1);

        if(count($nextProduct)){
            return $nextProduct->First()->Link();

        }else{
            $parentPage = CataloguePage::get()->byID($parentID);
            $nextCatalogue = CataloguePage::get()->filter(array('Sort:GreaterThan' => $parentPage->Sort))->Sort('Sort ASC')->limit(1);

            if(count($nextCatalogue)) $nextCatalogueID = $nextCatalogue->First()->ID;
            else $nextCatalogueID = CataloguePage::get()->sort('Sort ASC')->First()->ID;

            return Product::get()->filter('ParentID', $nextCatalogueID)->Sort('Sort ASC')->First()->Link();
        }
    }
}

/**
* Join table object for gallery images
*/
class Product_Images extends DataObject {
    static $db = array(
        'ProductID' => 'Int',
        'ImageID' => 'Int',
        'Caption' => 'Text',
        'SortOrder' => 'Int',
        'UseAsThumb' => 'Boolean(0)',
    );
}