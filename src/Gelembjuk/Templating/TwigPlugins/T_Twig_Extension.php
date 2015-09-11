<?php

class T_Twig_Extension extends Twig_Extension
{	
	protected $environment;

	public function initRuntime(\Twig_Environment $environment) {
		$this->environment = $environment;

	}
	public function getEnvironment() {
		return $this->environment;
	}
	public function getName()
	{
		return 't';
	}
	public function getFunctions()
	{
		// template format is 
		// {{ t($key,$group = '',$p1 = '', $p2 = '',$p3 = '',$p4 = '',$p5 = '') }}
		$extension = $this;

		return array(
			new Twig_SimpleFunction('t', function ($key,$group = '', $p1 = '', $p2 = '',$p3 = '',$p4 = '',$p5 = '') use($extension) {

				$application = $extension->getEnvironment()->getApplication();

				if (!is_object($application)) {
					// smarty is used out of context of Gelembjuk\WebApp package
					// just return empty string
					echo '';
					return ;
				}
				echo $application->getText($key,$group,$p1,$p2,$p3,$p4,$p5);
				
			})
			);
	}
	
}