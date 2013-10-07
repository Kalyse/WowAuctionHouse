<?php

/**
 * Base controller class
 */
class Controller_Abstract {
	/**
	 * @var View_Abstract
	 */
	private $view;
	/**
	 * @var Layout_Abstract
	 */
	private $layout;

	private $layoutEnabled = true;
	private $viewEnabled = true;

	/**
	 * @var Request_Abstract
	 */
	private $request;

	/**
	 * @param Router_route $route
     * @param Layout_Abstract $layout
     * @param View_Abstract $view
     * @param Request_Abstract $request
     * @throws Exception_404
	 * @return Controller_Abstract
	 */
	public static function factory( Router_Route $route, Layout_Abstract $layout, View_Abstract $view, Request_Abstract $request)
	{
		$className = "Controller_".ucfirst($route->getController());
		$view->setTemplate($route->getController()."/".$route->getAction().".tpl");
		try {
			$controller = new $className($layout, $view, $request);
			/* @var $controller Controller_Abstract */
		} catch (Exception $ex) {
			throw new Exception_404("Controller not found: ".$className);
		}
		return $controller;
	}


	public function __construct($layout, $view, $request)
	{
		$this->layout = &$layout;
		$this->view = &$view;
		$this->request = &$request;
	}

	public static function getActionMethod(Router_Route $route)
	{
		return ucfirst($route->getAction())."Action";
	}

	public function assign($var, $value)
	{
		$this->view->__set($var, $value);
	}

	public function assignLayout($var, $value)
	{
		$this->layout->__set($var, $value);
	}

	public function enableLayout($enable = true)
	{
		$this->layoutEnabled = $enable;
	}

	public function enableView($enable = true)
	{
		$this->viewEnabled = $enable;
	}

	public function isLayoutEnabled()
	{
		return $this->layoutEnabled;
	}

	public function isViewEnabled()
	{
		return $this->viewEnabled;
	}

	public function getRequestVar($var, $default = null)
	{
		return $this->request->getVar($var, $default);
	}
}

?>