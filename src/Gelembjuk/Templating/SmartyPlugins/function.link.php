<?php
/*
 * Smarty plugin to include links in templates easy.
 * It works only in contents of Gelembjuk\WebApp package and needs application instance
 * to redirect url generate requets to it
 * -------------------------------------------------------------
 * File:     function.link.php
 * Type:     function
 * Name:     link
 * Purpose:  outputs a url created with arguments
 * -------------------------------------------------------------
 */
function smarty_function_link($params, Smarty_Internal_Template $template)
{
	if (!function_exists('getApplicationInstance')) {
		// smarty is used out of context of Gelembjuk\WebApp package
		// just return empty string
		return '';
	}
	
	$application = getApplicationInstance();

	// extract controller as it must be checked in application to know what controller builds a link
	$controllername = $params['controller'];
	unset($params['controller']);
	
	return $application->makeUrl($controllername,$params);
}
?>
