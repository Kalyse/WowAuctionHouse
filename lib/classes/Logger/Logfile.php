<?php
require_once __DIR__.'/../../Interfaces/LogwriterInterface.php';
/**
 * "File" writer adapter for Logger
 *
 * @author lamy
 */
class Logger_Logfile implements LogwriterInterface
{
	/**
	 * @var string
	 */
	protected $logfilePath;

	/**
	 * @var resource
	 */
	protected $logfileHandle = null;

	public function __construct( $logfile, $ignoreNonexistent = false)
	{
		$this->logfilePath = $logfile;
		if( file_exists( $logfile) && !is_writable( $logfile))
		{
			if(!$ignoreNonexistent) {
				throw new Exception("$logfile is not writable");
			}
			$this->logfileHandle = false;
		}
		if( $this->logfileHandle !== false)
		{
			if( false === ($this->logfileHandle = fopen( $this->logfilePath, "a"))) {
				if( !$ignoreNonexistent) {
					throw new Exception("Unable to open $logfile for writing");
				}
				$this->logfileHandle = false;
			}
		}
	}

	public function __destruct()
	{
		if( $this->logfileHandle) {
			fclose( $this->logfileHandle);
		}
	}

	public function write($message)
	{
		if( $this->logfileHandle)
		{
			fseek($this->logfileHandle, 0, SEEK_END);
			//flock($this->logfileHandle, LOCK_EX);
			fwrite($this->logfileHandle, $message."\n");
			//flock($this->logfileHandle, LOCK_UN);
			fflush($this->logfileHandle);
		} else {
			throw new Exception("Write disabled");
		}
	}
}

?>