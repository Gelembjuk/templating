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
	$application = $template->getApplication();

	if (!is_object($application)) {
		return $key;
	}
	
	// OLD VERSION. for back compability
	if (!isset($params['k']) && !isset($params['key'])) {
		if (isset($params['_'])) {
			$params['k'] = $params['_'];
		} elseif (isset($params['__'])) {
			$params['k'] = $params['_'];
		}
	}

	for ($i = 1; $i <= 5; $i++){
		if (isset($params['t'.$i]) && !isset($params['p'.$i])) {
			$params['p'.$i] = $params['t'.$i];
		} 
	}
	// END old version

	$key = (isset($params['key'])?$params['key']:$params['k']);
	
	$group = (isset($params['group'])?$params['group']:$params['g']);
	
	return $application->getText($key,$group,$params['p1'],$params['p2'],$params['p3'],$params['p4'],$params['p5']);
}
?>
