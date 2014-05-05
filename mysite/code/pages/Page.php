<?php
class Page extends SiteTree {

	private static $db = array(
		'Attr' => 'MultiValueField',
		'ShowPDFLink' => 'Boolean(0)',
	);

	private static $allowed_children = array(
	);


	/**
	 * Metadata
	 */
	public function stripHtmlTags($str){
		return trim(preg_replace('/\s+/', ' ', strip_tags($str)));
	}

	public function onBeforeWrite(){
		if($this->Content && !$this->MetaDescription){
			$content = $this->stripHtmlTags($this->Content);

			if (strlen($content) > 160) {
				//limit hit!
				$string = substr($content, 0, 157);
				
				//stop on a word.
				$string = substr($string, 0, strrpos($string,' ')) . '...';
			
			}else{
				$string = $content;
			}

			$this->MetaDescription = $string;
		}
		parent::onBeforeWrite();
	}

	/**
	 * Main Content Fields
	 */
	/*public function getCMSFields(){
		$fields = parent::getCMSFields();

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}*/

	/**
	 * Settings Fields
	 */
	public function getSettingsFields(){
		$fields = parent::getSettingsFields();

		$fields->addFieldsToTab('Root.Settings', array(
			KeyValueField::create('Attr', 'Attributes', 
				array(	'path' => 'path',
						'stroke' => 'stroke',
						'stroke-width' => 'stroke-width',
						'stroke-opacity' => 'stroke-opacity',
						'stroke-linecap' => 'stroke-linecap',
						'stroke-linejoin' => 'stroke-linejoin',
						'stroke-dasharray' => 'stroke-dasharray',
						'fill' => 'fill',
						'fill-opacity' => 'fill-opacity',
						'fill-rect' => 'fill-rect',
						'transform' => 'transform',
						'height' => 'height',
						'width' => 'width',
						'x' => 'x',
						'y' => 'y',
						'ellipse-color' => 'ellipse-color',
						'text-color' => 'text-color',
						'path2' => 'path2',
						'border' => 'border',
						'border2' => 'border2',
				)),
			CheckboxField::create('ShowPDFLink', 'Show PDF Link ?', $this->ShowPDFLink)

		));

		if(!Member::currentUser()->inGroup('administrators')){
			$fields = $fields->makeReadonly();
		}

		return $fields;
	}

	/**
	 * CMSTreeClasses
	 */
	public function CMSTreeClasses(){
		Requirements::css("mysite/css/TreeSelector.css"); 
		
		$classes = parent::CMSTreeClasses();

		if($this->ClassName == "Product"){
			
			if($this->Stock < 1){
				$classes .= " no-stock";
			}
		}

		return $classes;
	}
}
class Page_Controller extends ContentController {

	private static $allowed_actions = array(
		// 'index',
		'MiniCartForm',
		'pdf'
	);

	/**
	 * Init
	 */
	public function init() {
		parent::init();

		// setlocale(LC_TIME, i18n::get_locale() . ".UTF8");
		// i18n::set_locale('fr_FR');

/*		Requirements::combine_files('css/styles.min.css', array(
			"themes/lilimel/stylesheets/app.css",
		));*/

		Requirements::combine_files('scripts.min.js', array(
			"themes/lilimel/bower_components/jquery/dist/jquery.min.js",
			"themes/lilimel/bower_components/jquery-migrate/jquery-migrate.min.js",
			"themes/lilimel/bower_components/foundation/js/foundation/foundation.js",
			"themes/lilimel/bower_components/foundation/js/foundation/foundation.interchange.js",
			"themes/lilimel/bower_components/foundation/js/foundation/foundation.topbar.js",
			"themes/lilimel/bower_components/foundation/js/foundation/foundation.accordion.js",
			"themes/lilimel/bower_components/flexslider/jquery.flexslider-min.js",
			"themes/lilimel/bower_components/colorbox/jquery.colorbox-min.js",
			"themes/lilimel/bower_components/jquery-zoom/jquery.zoom.min.js",
			"themes/lilimel/bower_components/Snap.svg/dist/snap.svg-min.js",
			"themes/lilimel/bower_components/jquery-resize/jquery.ba-resize.min.js",
			"themes/lilimel/bower_components/jquery-hammerjs/jquery.hammer-full.min.js",
			"themes/lilimel/js/app.js",
		));

		Requirements::block('swipestripe/css/Shop.css');
		Requirements::block('blog/css/blog.css');
		Requirements::block('newsletter/css/SubscriptionPage.css');
		Requirements::block(FOUNDATION_FORM_DIR . '/css/foundationforms.css');
		Requirements::block(FRAMEWORK_DIR .'/thirdparty/jquery/jquery.js');
		Requirements::block(THIRDPARTY_DIR . '/jquery-validate/lib/jquery.form.js');
		Requirements::block(THIRDPARTY_DIR . '/jquery-validate/jquery.validate.pack.js');
	}

    public function FeaturedProducts(){
    	$products = Product::get()
    				->innerJoin("Product_Images", "\"Product_Images\".\"ProductID\" = \"Product\".\"ID\"")
    				->filter(array('Price:GreaterThan' => 0, /*'Stock:GreaterThan' => 0,*/ 'FeaturedProduct' => 1));

    	if($this->ClassName == "Product") $products = $products->exclude(array('ID' => $this->ID));

    	return $products->limit(5);
    }

	public function MiniCartForm() {

		return CartForm::create(
			$this, 
			'MiniCartForm'
		)->disableSecurityToken();
	}

	public function getPDFHeader() {
		$siteConfig = SiteConfig::current_site_config();
		$title = $siteConfig->Title;
		$img = "<p><img src='themes/lilimel/img/logo-lilimel.png'/></p>";
		// $img = "<p><img src='" . $siteConfig->Logo()->setWidth(250)->AbsoluteURL . "'/></p>";

		return "$img<hr/>";
		// return "<h1>$title</h1>$img<hr/>";
	}

	public function PDFStream(){
		
		$pdf = new SS_DOMPDF();

		$title = $this->Title;
		// $content = $this->getPDFHeader() . "<h2>$title</h2>";
		$content = '';

		if($this->data()->Content){
			$content .= $this->data()->Content;
		}

		Requirements::clear();

		$html = $this->customise(array('Content' => $content))->renderWith('PDFPage');
		
		$pdf->setHTML($html);

		$this->extend('updatePDFStream', $pdf);

		$pdf->render();
		
		header('Content-type: application/pdf');
		return $pdf->stream(Convert::raw2url($this->Title) . '.pdf', array('Attachment' => 0));
	}

	public function pdf(){
		return $this->PDFStream();
	}
	
	public function StreamLink() {
		if($this->ShowPDFLink){
			$streamLink = new Varchar();

			$streamLink = Controller::join_links($this->Link(), 'pdf');

			$this->extend('updateStreamLink', $streamLink);

			return $streamLink;
		}
		return false;
	}

	public function getTotalCartItems() {
		$numItem = 0;
		foreach ($this->Cart()->Items() as $item) {
			$numItem += $item->Quantity;
		}
		if($numItem > 1) $numItem = $numItem . " articles";
		else $numItem = $numItem . " article";
		return $numItem;
	}
}