<?php

class T_Twig_Extension extends Twig_Extension
{
	public function getName()
	{
		return 't';
	}
	public function getFunctions()
	{
		// template format is 
		// {{ t($key,$group = '',$p1 = '', $p2 = '',$p3 = '',$p4 = '',$p5 = '') }}
		return array(
			new Twig_SimpleFunction('link', function ($key,$group = '', $p1 = '', $p2 = '',$p3 = '',$p4 = '',$p5 = '') {
				if (!function_exists('getApplicationInstance')) {
					// smarty is used out of context of Gelembjuk\WebApp package
					// just return empty string
					echo '';
					return ;
				}
	
				$application = getApplicationInstance();

				echo $application->getText($key,$group,$p1,$p2,$p3,$p4,$p5);
				
			})
			);
	}
	
}