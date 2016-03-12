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
	use TemplatingTrait;

	/**
	 * Init plugins and extra plugins on the engine
	 * 
	 * @param string|array Path(s) to plugins sirectory
	 */
	protected function initPlugins($plugins = '') {
		// add plugis paths
		if (is_dir(dirname(__FILE__).'/SmartyPlugins')) {
			$this->addPluginsDir(dirname(__FILE__).'/SmartyPlugins');
		}
		
		if (isset($plugins)) {
			if (is_array($plugins)) {
				foreach ($plugins as $path) {
					$this->addPluginsDir($path);
				}
			} elseif ($plugins != '') {
				$this->addPluginsDir($plugins);
			}
		}
	}
	/**
	 * Returns a path to a directory with plugins. Can be used for cases
	 * when plugins should be used with an external application
	 * 
	 * @return string Path to a directory with native plugins
	 */
	public function getNativePluginsDir() {
		if (is_dir(dirname(__FILE__).'/SmartyPlugins')) {
			return dirname(__FILE__).'/SmartyPlugins';
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
	 * Fetch prepared file template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	protected function fetchFromFile() {
		$this->setTemplateDir($this->template_dir_orig);
		$this->setCompileDir($this->compile_dir_orig);
		return $this->fetch($this->template.'.'.$this->templates_extension);
	}
	
	/**
	 * Fetch prepared string template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	protected function fetchFromString() {
		$this->setCompileDir($this->compile_dir_orig);
		return $this->fetch('string:'.$this->template_data);
	}
}
