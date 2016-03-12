<?php

/**
* This trait helps to build wraper classes for popular template engines to make all them to have one interface
* The trait implements some functions that are commn for all templating engines wrappers
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

trait TemplatingTrait {
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
	* Templates compilation directory. Path to a directory
	*
	* @var string
	*/
	protected $compile_dir_orig;
	/**
	 * Template variables 
	 * 
	 * @var array
	 */
	protected $template_vars = array();
	/**
	 * Reference to an application object that uses a templating class
	 * 
	 * @var array
	 */
	protected $application = null;
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
		
		$this->template_dir_orig = '';
		
		if ($options['templatepath'] != '') {
			$this->template_dir_orig = $options['templatepath'];
		}
		
		// compile dir is required and must be writable
		$this->compile_dir_orig  = $options['compiledir'];
		
		$this->caching = $options['usecache'];
		
		if ($this->caching) {
			$this->cache_dir    = $options['cachedir'];
		}
		
		$this->templates_extension = 'tpl';
		
		if ($options['extension'] != '') {
			$this->templates_extension = $options['extension'];
		}
		
		$this->initPlugins($options['templatingpluginsdir']);
		
		$this->template_vars = array();
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
		
		// if we want to use file system templtes then time to check
		$this->checkFileSystemTemplatingConfigured();
	}
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $html Tempate document as a string
	 */
	public function setTemplateData($html) {
		$this->template = '';
		$this->template_data = $html;
		
		// if we want to use file system templtes then time to check if all is set
		$this->checkStringTemplatingConfigured();
	}
	/**
	 * Set template as string. It is HTML document to fetch later as a template
	 * 
	 * @paral string $filepath Template file name. Same rules as for setTemplate
	 * @param array $options Array of options. Now only one option can eb used `extensionpresent` = true means template fiel path includes extension
	 * 
	 * @return boolean True if template file exists in a templates path
	 */
	public function checkTemplateExists($filepath,$options = array(),$tmplpath = '') {
		
		if (is_array($this->template_dir_orig) && $tmplpath == '') {
			foreach ($this->template_dir_orig as $path) {
				$exists = $this->checkTemplateExists($filepath,$options,$path);
				
				if ($exists) {
					return true;
				}
			}
			
			return false;
		}
		
		
		
		$filepath = (($tmplpath != '')?$tmplpath:$this->template_dir_orig).$filepath;

		if (!$options['extensionpresent']) {
			$filepath .= '.'.$this->templates_extension;
		}
		
		return file_exists($filepath);
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
			return $this->fetchFromFile();
		} else {
			return $this->fetchFromString();
		}
	}
	/**
	 * Check if there are all settings ready to do oprtation with templates saved in filesystem
	 * This is standard set of checks. We expect many engines can use only this
	 * 
	 * @return boolean True on success and raises exception if there are problems
	 */
	protected function checkFileSystemTemplatingConfigured_PathCompileCache() {
		if (is_array($this->template_dir_orig)) {
			$found = false;
			
			foreach ($this->template_dir_orig as $path) {
				if ($path != '' && is_dir($path)) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				throw new \Exception('Templates directory is not set');
			}
		} else {
			if (trim($this->template_dir_orig) == '') {
				throw new \Exception('Templates directory is not set');
			}
			if (!is_dir($this->template_dir_orig)) {
				throw new \Exception(sprintf('Templates directory %s not found',$this->template_dir_orig));
			}
		}
		if (trim($this->compile_dir_orig) == '') {
			throw new \Exception('Templates compile directory is not set');
		}
		if (!is_dir($this->compile_dir_orig)) {
			throw new \Exception(sprintf('Templates compile directory %s not found',$this->compile_dir_orig));
		}
		if (!is_writable($this->compile_dir_orig)) {
			throw new \Exception(sprintf('Templates compile directory %s is not writable',$this->compile_dir_orig));
		}
		if ($this->caching) {
			// if cache is On the cache path must be present
			if (!is_dir($this->cache_dir)) {
				throw new \Exception(sprintf('Templates cache directory %s not found',$this->cache_dir));
			}
			if (!is_writable($this->cache_dir)) {
				throw new \Exception(sprintf('Templates cache directory %s is not writable',$this->cache_dir));
			}
		}
		return true;
	}
	/**
	 * Check if there are all settings ready to do oprtation with templates saved in filesystem
	 * 
	 * @return boolean True on success and raises exception if there are problems
	 */
	protected function checkFileSystemTemplatingConfigured() {
		return $this->checkFileSystemTemplatingConfigured_PathCompileCache();
	}
	/**
	 * Check if there are all settings ready to do oprtation with templates presented as a string
	 * 
	 * @return boolean True on success and raises exception if there are problems
	 */
	protected function checkStringTemplatingConfigured() {
		return true;
	}
	/**
	 * Init plugins and extra plugins on the engine
	 * 
	 * @param string|array $plugins Path(s) to plugins sirectory
	 */
	protected function initPlugins($plugins = '') {
		return true;
	}
	/**
	 * Returns application object reference
	 * 
	 * @return object Application object
	 */
	public function getApplication() {
		return $this->application;
	}
	/**
	 * Sets application object referehce. Application can be used in plugins
	 * 
	 * @param object $application Application object
	 */
	public function setApplication($application) {
		$this->application = $application;
	}
	/**
	 * Returns a path to a directory with plugins. Can be used for cases
	 * when plugins should be used with an external application
	 * 
	 * @return string Path to a directory with native plugins
	 */
	public function getNativePluginsDir() {
		return '';
	}
	/**
	 * Fetch prepared file template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	abstract protected function fetchFromFile();
	
	/**
	 * Fetch prepared string template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	abstract protected function fetchFromString();
}
