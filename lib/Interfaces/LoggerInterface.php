<?php

interface LoggerInterface
{
	const DEBUG		= 1;
	const INFO		= 2;
	const NOTICE	= 3;
	const ERROR		= 4;
	const FATAL		= 5;

	public function __construct( LogwriterInterface $writer, $intMinLevel = self::INFO, $filters = array());
	public function debug( $message, $subsystem = null);
	public function info( $message, $subsystem = null);
	public function notice( $message, $subsystem = null);
	public function error( $message, $subsystem = null);
	public function fatal( $message, $subsystem = null);
}

?>