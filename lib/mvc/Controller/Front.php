<?php
class Controller_Front {
	protected $router = null;

	/**
	 *
	 * @param array $requestData
	 * @param Router_Interface $router
	 */
	public function __construct( $requestData, Router_Interface $router = null)
	{
		$this->router = $router;
	}
}

?>