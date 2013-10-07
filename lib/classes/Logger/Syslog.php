<?php

/**
 * "Syslog" writer adapter for Logger
 *
 * @author lamy
 */
class Logger_Syslog implements LogwriterInterface
{

	/**
	 * Create a new syslog writer. Arguments are identical to openlog()
	 *
	 * @param string $ident
	 * @param int $option
	 * @param int $facility
	 * @throws Exception
	 */
	public function __construct( $ident, $option = LOG_ODELAY, $facility = LOG_USER)
	{
		openlog($ident, $option, $facility);
	}

	public function write($message)
	{
		syslog(1, $message);
	}
}

?>