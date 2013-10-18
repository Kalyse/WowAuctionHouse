<?php

/**
 * Route by URL variable
 *
 * @author TLamy
 */
class Router_Urlvar
		extends Router_Abstract
		implements Router_Interface {

	/**
	 * Get array(Controller, Action) from request
	 * @param Request_Abstract $request
	 * @return Router_Route
	 */
	public function route( Request_Abstract $request)
	{
		if( null === ($doVar = $request->getVar("do"))) {
			return new Router_Route( null, null);
		}
		$parts = explode("|", $doVar);
		switch( count( $parts))
		{
			case 1:
				return new Router_Route($parts[0], null);

			case 2:
				return new Router_Route($parts[0], $parts[1]);

			default:
				throw new Exception_404( "Malformed route variable '$doVar'");
		}
		/* not reached */
		return null;
	}
}

?>