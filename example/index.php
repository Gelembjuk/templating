<?php 

/**
 * Example. Usage of Gelembjuk/Templating . Example uses Smarty wrapper
 * 
 * This example is part of gelembjuk/templating package by Roman Gelembjuk (@gelembjuk)
 */

// ==================== CONFIGURATION ==================================
// path to your composer autoloader
require ('vendor/autoload.php');

$thisdirectory = dirname(__FILE__) . '/'; // get parent directory of this script

// TEMPLATE ENGINE

// SMARTY
$engineclass = 'Gelembjuk\\Templating\\SmartyTemplating';
$templatesdir = $thisdirectory .'template_smarty/';

// TWIG
//$engineclass = 'Gelembjuk\\Templating\\TwigTemplating';
//$templatesdir = $thisdirectory .'template_twig/';



// this is the template that will include all other pages inside it
// just standard trick to have header and footer same for all pages
$out_template = 'main';

$templateroptions = array(
		'templatepath' => $templatesdir, // templates dir is in same folder with this script
		'compiledir' => $thisdirectory .'compile_dir/',
		'usecache' => false, // don't cache 
		'extension' => 'htm' // our templates files will have htm extension
	);
	
// create and init templater
$templater = new $engineclass();
$templater->init($templateroptions);

// ==================== WEB APP LOGIC ==================================

$page = $_REQUEST['page'];

// set template and prepare variables array
$template = 'default';
$template_variables = array();

if ($page == 'page1') {
	// display page 1
	$template = 'page1';
	
} elseif ($page == 'page2') {
	// display page 2
	$template = 'page2';
	
	$template_variables['message'] = "Hello on Page 1";
	
} elseif ($page == 'page3') {
	// display page 3
	$template = 'page3';
	
	$template_variables['myarray'] = array(
		'Option 1',
		'Option 2',
		'Option 3',
		'Option 4',
		);
	
} else {
	// display default page
	// template `default` is already set
	
	$template_variables['hellomessage'] = 'Hello world';
} 

// add some variable to display on any page
$template_variables['sitetitle'] = 'My Site Example';

$templater->setVars($template_variables);

$templater->setTemplate($template);

// fetch page page content
$pagecontent = $templater->fetchTemplate();

// now insert page content to main template
$templater->setVar('PAGECONTENT',$pagecontent);
$templater->setTemplate($out_template);

$html = $templater->fetchTemplate();

// output our html
header('Content-Type: text/html; charset=utf-8');
echo $html;
exit;
