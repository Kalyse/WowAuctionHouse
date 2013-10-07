<?php

require_once ('lib/Interfaces/DBInterface.php');

/**
 *
 * @author TLamy
 *
 */
class DB
		extends PDO
		implements DBInterface
{
	/**
	 * @var LoggerInterface
	 */
	protected $_Logger;
	/*
	static private $_instance = null;

	public static function getInstance()
	{
		if( !static::$_instance)
		{
			throw new Exception("No DB instance");
		}
		return static::$_instance;
	}

	public static function setInstance( DBInterface $db)
	{
		static::$_instance = $db;
	}
*/
	public function __construct($host, $username, $password, $database)
	{
		parent::__construct("mysql:dbname=".$database.";host=".$host, $username, $password);
		$this->exec("SET names utf8");
	}

	public function registerLogger( LoggerInterface $Logger)
	{
		$this->_Logger = $Logger;
	}

	public function bindObject( PDOStatement $stmt, stdClass $object)
	{
		foreach( get_object_vars($object) as $var=>$value) {
			$stmt->bindParam(':'.$var, $value);
		}
	}
	/**
	 * @param stdClass $object
	 * @param unknown_type $names
	 * @return multitype:unknown
	 */
	public function getBindFromObject( stdClass $object, $names)
	{
		$result = array();
		foreach( get_object_vars($object) as $var=>$value) {
			if(in_array($var, $names)) {
				$result[':'.$var] = $value;
			}
		}
		return $result;
	}

	public function exec($statement)
	{
		if( $this->_Logger) {
			$this->_Logger->debug("Exec: ".$statement);
		}
		return parent::exec($statement);
	}

	public function query($statement)
	{
		if( $this->_Logger) {
			$this->_Logger->debug("Query: ".$statement);
		}
		$result = parent::query($statement);
		if( !$result) {
			throw new Exception("Error: ".$this->errorInfo()."\n".$statement);
		}
		return $result;
	}
}

?>