<?php

require_once __DIR__.'/../Interfaces/LoggerInterface.php';
require_once __DIR__.'/../Interfaces/LogwriterInterface.php';

/**
 * Logging
 *
 */
class Logger implements LoggerInterface
{
	protected $loggingEnabled = true;

	protected $minLevel;

	/**
	 * @var LogwriterInterface
	 */
	protected $writer;

	protected $appendNewline = true;

	protected $filters = array();

	/**
	 * @param LogwriterInterface $writer
	 * @param int $intMinLevel
	 * @param array $filters
	 * @throws Exception
	 */
	public function __construct( LogwriterInterface $writer,
			$intMinLevel = LoggerInterface::INFO,
			$filters = array())
	{
		$this->writer   = $writer;
		$this->minLevel = $intMinLevel;
		$this->filters  = $filters;
	}

	public function getLogLevelName( $intLevel)
	{
		$strLevel = null;
		switch( $intLevel) {
			case LoggerInterface::DEBUG:
				$strLevel = "DEBUG";
				break;

			case LoggerInterface::INFO:
				$strLevel = "INFO";
				break;

			case LoggerInterface::NOTICE:
				$strLevel = "NOTICE";
				break;

			case LoggerInterface::ERROR:
				$strLevel = "ERROR";
				break;

			case LoggerInterface::FATAL:
				$strLevel = "FATAL";
				break;

			default:
				$strLevel = "UNKNOWN-".$intLevel;
		}
		return $strLevel;
	}


	/**
	 * Log a message
	 *
	 * @param string $message
	 * @param int $level
	 * @param string $subsystem
	 * @return boolean Message logged
	 */
	protected function logMessage( $message, $level, $subsystem = null)
	{
		if( $message === null)
		{
			try {
				throw new Exception();
			} catch (Exception $ex) {
				$message = "NULL\n".$ex->getTraceAsString();
			}
		}
		if( $this->filter($level, $subsystem))
		{
			$this->writer->write( sprintf("[%s] [%s] %s",
					$this->getTimestamp(), $this->getLogLevelName( $level), $message));
			return true;
		}
		return false;
	}

	/**
	 * Run message through filters. Return true if message passed all filters (should be written)
	 * @param int $level
	 * @param string $subsystem
	 * @return boolean
	 */
	protected function filter( $level, $subsystem)
	{
		if( !empty($this->filters) && !empty($subsystem)) {
			return isset($this->filters[$subsystem]) && $level >= $this->filters[$subsystem];
		}
		return $level >= $this->minLevel;
	}

	public function debug( $message, $subsys = null)
	{
		return $this->logMessage( $message, LoggerInterface::DEBUG, $subsys);
	}

	public function info( $message, $subsys = null)
	{
		return $this->logMessage( $message, LoggerInterface::INFO, $subsys);
	}

	public function notice( $message, $subsys = null)
	{
		return $this->logMessage( $message, LoggerInterface::NOTICE, $subsys);
	}

	public function error( $message, $subsys = null)
	{
		return $this->logMessage( $message, LoggerInterface::ERROR, $subsys);
	}

	public function fatal( $message, $subsys = null)
	{
		return $this->logMessage( $message, LoggerInterface::FATAL, $subsys);
	}

	protected function getTimestamp()
	{
		list($micro) = explode(" ", microtime(false));
		return date("Y-m-d H:i:s").sprintf(".%03d", substr(round($micro * 1000), -3));
	}
}

?>