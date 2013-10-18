<?php
/**
 * View implementation using Twig
 */
class View_Twig
		extends View_Abstract
{
	public function __construct(MvcApplication $app, $templatePath = null)
	{
		parent::__construct($app, $templatePath);
		$loader = new Twig_Loader_Filesystem($this->getTemplatePath());
		$this->engine = new Twig_Environment($loader, array('autoescape'=>false));

	}
	public function render()
	{
		return $this->engine->render($this->template, $this->vars);
	}

}

?>