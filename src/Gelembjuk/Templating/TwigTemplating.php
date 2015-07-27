<?php

/**
* This class inherits all functionality from Twig Template Engine and implements TemplatingInterface interface
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

class TwigTemplating  extends \Twig_Environment implements TemplatingInterface {
	use TemplatingTrait;
	
	public function __construct($loader = null) {
		parent::__construct($loader);
	}
	/**
	 * Init plugins and extra plugins on the engine
	 * 
	 * @param string|array Path(s) to plugins sirectory
	 */
	protected function initPlugins($plugins = '') {
		if (is_dir(dirname(__FILE__).'/TwigPlugins')) {
			$this->loadPluginsFromDirectory(dirname(__FILE__).'/TwigPlugins');
		}
		
		if (isset($plugins)) {
			if (is_array($plugins)) {
				foreach ($plugins as $path) {
					$this->loadPluginsFromDirectory($path);
				}
			} elseif ($plugins != '') {
				$this->loadPluginsFromDirectory($plugins);
			}
		}
	}
	/**
	 * Empty all previously set template variables
	 */
	public function cleanVars() {
		$this->template_vars = array();
	}
	/**
	 * Set new template variable
	 * 
	 * @paral string $name Variable name
	 * @paral mixed $val Variable value. Can be different type, depends on supported types of engine 
	 */
	public function setVar($name,$val) {
		$this->template_vars[$name] = $val;
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
		return $this->template_vars[$name];
	}
	/**
	 * Fetch prepared file template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	protected function fetchFromFile() {
		// TODO
		// we don't check if there was loader already set. but it would be useful like optimization
		$loader = new \Twig_Loader_Filesystem($this->template_dir_orig);
		$this->setLoader($loader);
		return $this->render($this->template.'.'.$this->templates_extension, $this->template_vars);
	}
	
	/**
	 * Fetch prepared string template and return complete html document
	 * 
	 * @return string HTML document with all data included 
	 */
	protected function fetchFromString() {
		// loader is created each time as value of index can be changed
		// maybe there is way to do this without creating new loader each time
		$loader = new \Twig_Loader_Array(array('index' => $this->template_data));
		$this->setLoader($loader);
		
		return $this->render('index', $this->template_vars);
	}
	/**
	 * Load plugins from a directory 
	 */
	protected function loadPluginsFromDirectory($dirpath) {
		if (!is_dir($dirpath)) {
			return false;
		}
		
		$contents = @scandir($dirpath);
		
		if (is_array($contents)) {
			foreach ($contents as $file) {
				if (substr($file,-4) == '.php') {
					$class = '\\' . substr($file,0,-4);
					
					if($class != '') {
						// is this secure enough?
						// we don't use autoloader as we don't know how taht plugins are stored
						require_once($dirpath.'/'.$file);
						
						try {
							$twig_object = new $class();
						
							if ($twig_object instanceof \Twig_Extension) {
								$this->addExtension($twig_object);
							}
						} catch (\Extension $e) {
						}
					}
				}
			}
		}
	}
}
