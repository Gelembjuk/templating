<?php
/*
 * Smarty plugin to make widgets building easier.
 * -------------------------------------------------------------
 * File:     function.widget.php
 * Type:     function
 * Name:     widget
 * Purpose:  Universal widget plugin. To include some views in other views
 * -------------------------------------------------------------
 */
function smarty_function_widget($params, Smarty_Internal_Template $template)
{

	$application = $template->getApplication();

	if (!is_object($application)) {
		return 'Can not get application object from the template object';
	}

    $widgetName = $params['name'] ?? '';

    if (empty($widgetName)) {
        $widgetName = $params['n'] ?? '';
    }

    if (empty($widgetName)) {
        return 'Widget name is not set';
    }

    $widget = $application->getWidget($widgetName);
    
    return $widget->render($params);
}


