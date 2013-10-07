<?php
/**
 * Router_Route, the Router's result
 */
class Router_Route {
	protected $controller;
	protected $action;

	public function __construct($controller, $action)
	{
		if($controller === null) {
			$controller="index";
		}
		if($action === null) {
			$action="index";
		}
		if( !is_scalar($controller)) {
			throw new Exception("\$controller is not scalar: ".gettype($controller));
		}
		if( !is_scalar($action)) {
			throw new Exception("\$action is not scalar: ".gettype($action));
		}
		$this->controller = $controller;
		$this->action     = $action;
	}

	/**
	 * @return string
	 */
	public function getController()
	{
		return $this->controller ? $this->controller : "index";
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action ? $this->action : "index";
	}
}

?>