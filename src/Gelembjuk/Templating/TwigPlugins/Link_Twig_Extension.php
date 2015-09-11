<?php

class Link_Twig_Extension extends Twig_Extension
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
		return 'link';
	}
	public function getFunctions()
	{
		// template format is 
		// {{ link({'controller':'def','view':'aaa','id':user.id}) }}
		$extension = $this;

		return array(
			new Twig_SimpleFunction('link', function ($params) use($extension)  {
				$application = $extension->getEnvironment()->getApplication();

				if (!is_object($application)) {
					// smarty is used out of context of Gelembjuk\WebApp package
					// just return empty string
					echo '';
					return ;
				}

				// extract controller as it must be checked in application to know what controller builds a link
				$controllername = $params['controller'];
				unset($params['controller']);

				if (empty($controllername) && isset($params['c'])) {
					$controllername = $params['c'];
					unset($params['c']);
				}
	
				echo $application->makeUrl($controllername,$params);
				
			})
			);
	}
	
}