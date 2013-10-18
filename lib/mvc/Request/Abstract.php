<?php
/**
 * Manage request variables
 */
abstract class Request_Abstract {
	protected $vars = array();

	public function __construct()
	{
		$this->vars = array();
	}

	/**
	 * Initialize request storage
	 *
	 * @note implementor would get it's data from superglobal
	 * @return boolean
	 */
	abstract public function init();

	/**
	 * Get initialized request object based on SAPI
	 *
	 * @throws Exception
	 * @return Request_Abstract
	 */
	static public function factory()
	{
		switch( php_sapi_name())
		{
			case "apache2handler":
				$result = new Request_Http();
				$result->init();
				break;

			case "cli":
				$result = new Request_Cli();
				$result->init();
				break;

			default:
				throw new Exception("Dont know how to handle SAPI '".php_sapi_name()."'");
		}
		return $result;
	}


	/**
	 * Get a parameter
	 * @param string $name
	 * @param mixed $default
	 * @param string $source
	 * @throws Exception_Argument
	 * @return multitype:
	 */
	public function getVar( $name, $default = null, $source = null)
	{
		if( !is_scalar( $name)) {
			throw new Exception("'$name' is not scalar");
		}
		if( $source !== null) {
			return $this->getVarFromSource( $name, $source, $default);
		}
		if( !isset( $this->vars[$name])) {
			return $default;
		}
		return $this->vars[$name];
	}

	protected function getVarFromSource( $name, $source, $default = null)
	{
		throw new Exception( get_class( $this)." does not support input sources");
	}
}

?>