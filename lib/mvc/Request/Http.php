<?php
/**
 * Manage HHTP request data
 */
class Request_Http
		extends Request_Abstract
{

	protected $getVars = array();
	protected $postVars = array();
	protected $cookieVars = array();
	protected $fileVars = array();

	/**
	 * @see Request_Abstract::init()
	 */
	public function init()
	{
		$this->vars = $_REQUEST;
		$this->getVars = array_keys($_GET);
		$this->postVars = array_keys($_POST);
		$this->cookieVars = array_keys($_COOKIE);
		$this->fileVars = $_FILES;
	}
}

?>