<?php

/**
 * Place all used DB methods here to ease mocking
 *
 * @author TLamy
 */
interface DBInterface {
	public function __construct( $host, $username, $password, $database);
	/**
	 * @return void
	 */
	public function bindObject( PDOStatement $stmt, stdClass $object);
}

?>