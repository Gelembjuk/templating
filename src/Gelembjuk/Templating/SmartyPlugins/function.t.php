<?php
/*
 * Smarty plugin to output text translation by a key and locale of application
 * It works only in contents of Gelembjuk\WebApp package and needs application instance
 * to redirect text getting requets to it
 * -------------------------------------------------------------
 * File:     function.t.php
 * Type:     function
 * Name:     link
 * Purpose:  outputs a text by key and some arguments. Locale is defined in an application
 * -------------------------------------------------------------
 */
function smarty_function_t($params, Smarty_Internal_Template $template)
{
	$key = (isset($params['key'])?$params['key']:$params['k']);
	
	if (!function_exists('getApplicationInstance')) {
		// smarty is used out of context of Gelembjuk\WebApp package
		// just return a key
		return $key;
	}
	
	$group = (isset($params['group'])?$params['group']:$params['g']);
	
	$application = getApplicationInstance();
	return $application->getText($key,$group,$params['p1'],$params['p2'],$params['p3'],$params['p4'],$params['p5']);
}
?>
