<?php

global $project;
$project = 'mysite';


global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

// Set the site locale
// setlocale(LC_ALL, 'fr_FR');
i18n::set_locale('fr_FR');
// i18n::set_default_locale('fr_FR');
LocalDateHelper::setLocale('fr_FR');

// Config::inst()->update('i18n', 'date_format', 'dd.MM.YYYY');
// Config::inst()->update('i18n', 'time_format', 'HH:mm');


Email::setAdminEmail("yvestrublin@gmail.com");

BlogEntry::allow_wysiwyg_editing();

BlogTree::$default_entries_limit = 5;


Object::useCustomClass('MemberLoginForm', 'EnhancedMemberLoginForm');

NewsletterAdmin::$template_paths = '/mysite/templates/email/newsletter/';

/*
 * Html Editor Config
 */
HtmlEditorConfig::get('cms')->setOptions(array('theme_advanced_blockformats' => "h3,h4,h5,h6,p,address,pre"));
HtmlEditorConfig::get('cms')->setOptions(array(
  "skin" => "default",
	"style_formats" => array(
		/*array(
			"title" => "Lead",
			"selector" => "p",
			"classes" => "lead"
		),
		array(
			"title" => "Subheader",
			"selector" => "h3, h4, h5, h6, p",
			"classes" => "subheader"
		),*/
		array(
			"title" => "Small",
			"inline" => "small"
		),
		array(
			"title" => "Label",
			"inline" => "span",
			"classes" => "label radius"
		),
		array(
			"title" => "Label - Secondary",
			"inline" => "span",
			"classes" => "label secondary radius"
		),
		array(
			"title" => "Button",
			"selector" => "a",
			"classes" => "button small"
		),
		/*array(
			"title" => "Thumbnail",
			"selector" => "a",
			"classes" => "th radius"
		),*/
		array(
			"title" => "List - No Bullets",
			"selector" => "ul",
			"classes" => "no-bullet"
		),
		array(
			"title" => "List - Disc",
			"selector" => "ul",
			"classes" => "disc"
		),
		array(
			"title" => "List - Circle",
			"selector" => "ul",
			"classes" => "circle"
		),
		array(
			"title" => "List - Square",
			"selector" => "ul",
			"classes" => "square"
		),
		array(
			"title" => "Inline List",
			"selector" => "ul",
			"classes" => "inline-list"
		)/*,
		array(
			"title" => "Panel",
			"inline" => "div",
			"classes" => "panel"
		),
		array(
			"title" => "Panel - Callout",
			"inline" => "div",
			"classes" => "panel callout"
		)*/
	)
));

// HtmlEditorConfig::get('cms')->setButtonsForLine(1, array());
// HtmlEditorConfig::get('cms')->setButtonsForLine(2, array());
// HtmlEditorConfig::get('cms')->setButtonsForLine(3, array());

HtmlEditorConfig::get('cms')->setButtonsForLine(1, 'pastetext', 'styleselect', 'formatselect', 'bold', 'italic', 'underline', 'strikethrough', 'separator', 'justifyleft', 'justifyright', 'justifycenter', 'justifyfull', 'separator', 'bullist', 'numlist', 'separator', 'image', 'link', 'unlink', 'anchor', 'separator', 'charmap', 'code', 'fullscreen');
HtmlEditorConfig::get('cms')->setButtonsForLine(2, 'tablecontrols');

/*
 * PHP Errors logs
 */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

SS_Log::add_writer(new SS_LogFileWriter('/home/yves/www/lilimel/logs/errors.txt'), SS_Log::WARN, '<=');

ini_set("log_errors", "On");
ini_set("error_log", "/home/yves/www/lilimel/logs/errors_php.txt");