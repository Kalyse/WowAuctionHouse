<?php

/**
 * Abstract Layout
 *
 * @author lamy
 */
abstract class Layout_Abstract extends View_Abstract
{
	const VIEWS_DIR = "Templates/layouts";

	public function __construct(MvcApplication $app, $templatePath = null)
	{
		$this->mvcApp = $app;
		if(null !== $templatePath) {
			$this->templatePath = $templatePath;
		} else {
			$this->templatePath = $this->mvcApp->getBasedir()."/".self::VIEWS_DIR;
		}
	}

}

?>