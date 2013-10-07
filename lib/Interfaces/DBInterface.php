<?php

/**
 * @author TLamy
 *
 */
interface DBInterface {
	public function __construct( $host, $username, $password, $database);
	/**
	 * @return void
	 */
	public function bindObject( PDOStatement $stmt, stdClass $object);
}

?>