<?php
/**
* Common interface for different templating engines.
* This allows to change your templating engine easy without PHP changes in your app.
*
* LICENSE: MIT
*
* @category   Templating
* @package    Gelembjuk/Templating
* @copyright  Copyright (c) 2015 Roman Gelembjuk. (http://gelembjuk.com)
* @version    1.0
* @link       https://github.com/Gelembjuk/templating
*/

namespace Gelembjuk\Templating;

interface TemplatingInterface {
	/**
	 * Init a template engine. Accepts array of options
	 * Options are related to engine used. See list of options for each engine
	 * 
	 * Usually options are:
	 * templatepath - path to templates directory
	 * compiledir	- path to directory where to store temporary files created by engine for templates
	 * usecache	- (true|false) Torns cache On/Off . Depends on engine if it supports cache
	 * cachedir	- If cache is On then directory for cache files can be needed
	 * extension	- Extension of template files. Default is different for different engines
	 * templatingpluginsdir - Path (or array of paths) to folders where an engine extra plugins are stored
	 * 
	 * @param array $options
	 */
	public function init($options);
	/**
	 * Empty all previously set template variables
	 */
	public function cleanVars();
	/**
	 * Set new template variable
	 * 
	 * @paral string $name Variable name
	 * @paral mixed $val Variable value. Can be different type, depends on supported types of engine 
	 */
	public function setVar($name,$val);
	/**
	 * Set many variables 
	 * 
	 * @paral array $list List of key/value pairs to set as variables
	 */
	public function setVars($list);
	/**
	 * Get previously set variable
	 * 
	 * @paral string $name Variable name
	 * 
	 * @return mixed Variable value
	 */
	public function getVar($name);
	/**
	 * Set template file name to fetch later. It is only file name without extension.
	 * File must be in the configured templates folder
	 * 
	 * @paral string $filepath Tempate file
	 */
	public function setTemplate($filepath);
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $html Tempate document as a string
	 */
	public function setTemplateData($html);
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $filepath Template file name. Same rules as for setTemplate
	 * @param array $options Array of options. Now only one option can eb used `extensionpresent` = true means template fiel path includes extension
	 * 
	 * @return boolean True if template file exists in a templates path
	 */
	public function checkTemplateExists($filepath,$options = array());
	/**
	 * Fetch prepared template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	public function fetchTemplate();
	/**
	 * Fetch template from given string and return complete html document
	 * 
	 * @param string $htmlstring Template to fetch
	 * 
	 * @return string HTML document with all data included 
	 */
	public function fetchString($htmlstring);
	/**
	 * Fetch prepared template and dislay as html page
	 */
	public function displayPage();
}
