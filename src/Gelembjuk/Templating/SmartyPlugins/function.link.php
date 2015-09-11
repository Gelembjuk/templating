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
	$application = $template->getApplication();

	if (!is_object($application)) {
		return '';
	}

	// extract controller as it must be checked in application to know what controller builds a link
	$controllername = $params['controller'];
	unset($params['controller']);

	if (empty($controllername) && isset($params['c'])) {
		$controllername = $params['c'];
		unset($params['c']);
	} 
	
	return $application->makeUrl($controllername,$params);
}
?>
