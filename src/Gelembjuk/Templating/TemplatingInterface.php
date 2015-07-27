<?php
/**
* Common interface for different templating engines.
* This allows to change your templating engine easy without PHP changes in your app.
* 
* The interface presents only functions that must be done in wrapper classes.
* Other part of cuntionality is done in the trait TemplatingTrait
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
}
