<?php

/**
 * Routing component
 *
 * @author TLamy
 */
abstract class Router_Abstract {
	/**
	 * @param Request_Abstract $request
	 * @return Router_Route
	 */
	abstract public function route( Request_Abstract $request);
}

?>