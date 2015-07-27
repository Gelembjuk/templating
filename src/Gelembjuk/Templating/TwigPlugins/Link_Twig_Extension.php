<?php

class Link_Twig_Extension extends Twig_Extension
{
	public function getName()
	{
		return 'link';
	}
	public function getFunctions()
	{
		// template format is 
		// {{ link({'controller':'def','view':'aaa','id':user.id}) }}
		return array(
			new Twig_SimpleFunction('link', function ($params) {
				if (!function_exists('getApplicationInstance')) {
					// smarty is used out of context of Gelembjuk\WebApp package
					// just return empty string
					echo '';
					return ;
				}
	
				$application = getApplicationInstance();

				// extract controller as it must be checked in application to know what controller builds a link
				$controllername = $params['controller'];
				unset($params['controller']);
	
					echo $application->makeUrl($controllername,$params);
				
			})
			);
	}
	
}