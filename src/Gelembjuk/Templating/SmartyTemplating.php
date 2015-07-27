<?php

/**
* This class inherits all functionality from Smarty and implements TemplatingInterface interface
* The class can be easy replaced with other class implementing same interface and this allows to
* change your templating engine easy without PHP changes in your app.
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

class SmartyTemplating  extends \Smarty implements TemplatingInterface {
	/**
	* Template file name to process. This is a file name without extension. Can be relative path
	*
	* @var string
	*/
	protected $template;
	/**
	* Template as a string instead if a file
	*
	* @var string
	*/
	protected $template_data;
	/**
	* Templates directory. Path to a directory
	*
	* @var string
	*/
	protected $template_dir_orig;
	/**
	* Extension of template files. Defauls is .tpl
	*
	* @var string
	*/
	protected $templates_extension;
	
	/**
	 * Init a template engine. Accepts array of options
	 * 
	 * templatepath - path to smarty templates directory
	 * compiledir	- path to templates compilation directory 
	 * usecache	- (true|false) Torns cache On/Off .
	 * cachedir	- If cache is On then directory for cache files
	 * extension	- Extension of template files. Default is tpl
	 * templatingpluginsdir - Path (or array of paths) to folders where Smarty extra plugins are stored
	 * 
	 * @param array $options
	 */
	public function init($options) {
		
		$this->template = '';
		$this->template_data = '';
		
		if (!is_dir($options['templatepath'])) {
			throw new \Exception(sprintf('Smarty templates directory %s not found',$options['templatepath']));
		}
		
		// $this->template_dir is smarty native property
		$this->template_dir = $this->template_dir_orig = $options['templatepath'];
		
		// compile dir is required and must be writable
		$this->compile_dir  = $options['compiledir'];
		
		if (!is_dir($this->compile_dir)) {
			throw new \Exception(sprintf('Smarty compile directory %s not found',$this->compile_dir));
		}
		if (!is_writable($this->compile_dir)) {
			throw new \Exception(sprintf('Smarty compile directory %s is not writable',$this->compile_dir));
		}
		
		$this->caching = $options['usecache'];
		
		if ($this->caching) {
			$this->cache_dir    = $options['cachedir'];
			// if cache is On the cach path must be present
			if (!is_dir($this->cache_dir)) {
				throw new \Exception(sprintf('Smarty cache directory %s not found',$this->cache_dir));
			}
		}
		
		$this->templates_extension = 'tpl';
		
		if ($options['extension'] != '') {
			$this->templates_extension = $options['extension'];
		}
		
		// add plugis paths
		if (is_dir(dirname(__FILE__).'/SmartyPlugins')) {
			$this->addPluginsDir(dirname(__FILE__).'/SmartyPlugins');
		}
		
		if (isset($options['templatingpluginsdir'])) {
			if (is_array($options['templatingpluginsdir'])) {
				foreach ($options['templatingpluginsdir'] as $path) {
					$this->addPluginsDir($path);
				}
			} else {
				$this->addPluginsDir($options['templatingpluginsdir']);
			}
		}
	}
	/**
	 * Empty all previously set template variables
	 */
	public function cleanVars() {
		$this->clear_all_assign();
	}
	/**
	 * Set new template variable
	 * 
	 * @paral string $name Variable name
	 * @paral mixed $val Variable value. Can be different type, depends on supported types of engine 
	 */
	public function setVar($name,$val) {
		$this->assign($name,$val);
	}
	/**
	 * Set many variables 
	 * 
	 * @paral array $list List of key/value pairs to set as variables
	 */
	public function setVars($list) {
		foreach ($list as $k=>$v) {
			$this->setVar($k,$v);
		}
	}
	/**
	 * Get previously set variable
	 * 
	 * @paral string $name Variable name
	 * 
	 * @return mixed Variable value
	 */
	public function getVar($name) {
		return $this->get_template_vars($name);
	}
	/**
	 * Set template file name to fetch later. It is only file name without extension.
	 * File must be in the configured templates folder
	 * 
	 * @paral string $filepath Tempate file
	 */
	public function setTemplate($filepath) {
		$this->template = $filepath;
		$this->template_data = '';
	}
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $html Tempate document as a string
	 */
	public function setTemplateData($html) {
		$this->template = '';
		$this->template_data = $html;
	}
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $filepath Template file name. Same rules as for setTemplate
	 * @param array $options Array of options. Now only one option can eb used `extensionpresent` = true means template fiel path includes extension
	 * 
	 * @return boolean True if template file exists in a templates path
	 */
	public function checkTemplateExists($filepath,$options = array()) {
		
		$filepath = $this->template_dir_orig.$filepath;

		if (!$options['extensionpresent']) {
			$filepath .= '.'.$this->templates_extension;
		}
		
		return file_exists($filepath);
	}
	/**
	 * Fetch prepared template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	public function fetchTemplate() {
		
		if ($this->template != '' && 
			$this->template_data == '' &&
			!$this->checkTemplateExists($this->template)) {
			throw new \Exception(sprintf('Template file %s not found',$this->template));
		}
		
		if ($this->template != '') {
			return $this->fetch($this->template.'.'.$this->templates_extension);
		} else {
			return $this->fetch('string:'.$this->template_data);
		}
	}
	/**
	 * Fetch template from given string and return complete html document
	 * 
	 * @param string $htmlstring Template to fetch
	 * 
	 * @return string HTML document with all data included 
	 */
	public function displayPage() {
		$html = $this->fetchTemplate();
		echo $html;
		return true;
	}
	/**
	 * Fetch prepared template and dislay as html page
	 */
	public function fetchString($htmlstring) {
		$this->setTemplateData($htmlstring);
		return $this->fetchTemplate();
	}
}
