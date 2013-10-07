<?php

/**
 * A View with data container
 *
 * @author TLamy
 */
abstract class View_Abstract {
	const VIEWS_DIR = "Templates/views";

	protected $mvcApp;
	protected $engine;
	protected $vars;
	protected $template;
	protected $templatePath;
	protected $options;

	public function __construct(MvcApplication $app, $templatePath = null)
	{
		$this->mvcApp = $app;
		if(null !== $templatePath) {
			$this->templatePath = $templatePath;
		} else {
			$this->templatePath = $this->mvcApp->getBasedir()."/".self::VIEWS_DIR;
		}
	}

	public function setTemplate($path)
	{
		$this->template = $path;
	}


	public function __set($var, $value)
	{
		$this->vars[$var] = $value;
	}

	public function __get($var)
	{
		if( array_key_exists( $var, $this->vars))
		{
			return $this->vars[$var];
		}
		return null;
	}

	protected function getTemplatePath()
	{
		return $this->templatePath;
	}
}

?>