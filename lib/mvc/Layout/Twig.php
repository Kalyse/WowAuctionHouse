<?php

/**
 * Layout implementation using Twig
 *
 * @author lamy
 */
class Layout_Twig extends Layout_Abstract
{
	public function __construct(MvcApplication $app, $templatePath = null)
	{
		parent::__construct($app, $templatePath);
		$loader = new Twig_Loader_Filesystem($this->getTemplatePath());
		$this->engine = new Twig_Environment($loader);

	}
	public function render()
	{
		return $this->engine->render($this->template, $this->vars);
	}
}

?>