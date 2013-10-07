<?php

/**
 * Application Config container
 *
 */
class Application {
	static protected $instance = null;

	protected $startTime;
	/**
	 * @var DBInterface
	 */
	protected $db = null;

	/**
	 * @var array
	 */
	protected $config = array();
	/**
	 * @var bool
	 */
	protected $configInitialized = false;

	/**
	 * @var string
	 */
	protected $basePath;
	/**
	 * @var string
	 */
	protected $configPath;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	public function __construct( $basePath, $configPath = "config.ini")
	{
		$this->startTime = microtime(true);
		$this->basePath = realpath($basePath);
		$this->configPath = $configPath;
		if( $configPath[0] != '/') {
			$this->configPath = $this->basePath.'/'.$this->configPath;
		}
		self::$instance = $this;
	}


	/**
	 * @return Application
	 */
	public static function getInstance()
	{
		return self::$instance;
	}


	/**
	 * Sets the DB class to use and initializes it
	 * @param string $class
	 * @throws Exception
	 * @return DBInterface
	 */
	public function initDb( $class="DB")
	{
		$dbUser = $this->getConfig('dbuser');
		$dbPass = $this->getConfig('dbpass');
		$dbHost = $this->getConfig('dbhost');
		$dbName = $this->getConfig('dbname');
		if( $dbUser === null || $dbPass === null || $dbHost === null || $dbName === null)
		{
			throw new Exception("Check db config");
		}
		$this->db = new $class($dbHost, $dbUser, $dbPass, $dbName);

		return $this->db;
	}


	/**
	 * @return DBInterface
	 */
	static public function Db()
	{
		return static::getInstance()->getDB();
	}


	/**
	 * @return DB
	 */
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * Get configuration data for a key
	 * @param string $key
	 * @param mixed $default
	 * @return multitype:mixed
	 */
	public function getConfig( $key, $default = null)
	{
		if(!$this->configInitialized) {
			$this->readConfig();
		}
		if( array_key_exists($key, $this->config)) {
			return $this->config[$key];
		}
		return $default;
	}

	/**
	 * Read config, parse ini file, initialize private data so getConfig could work
	 * @throws Exception
	 */
	public function readConfig()
	{
		if(!file_exists($this->configPath)) {
			throw new Exception("Cannot read config from ".$this->configPath);
		}
		$this->config = parse_ini_file($this->configPath);
		$this->configInitialized = true;
	}

	public function getBasedir()
	{
		return $this->basePath;
	}

	public function getRuntime()
	{
		return microtime(true)-$this->startTime;
	}

	public function registerLogger($logger)
	{
		$this->logger = $logger;
	}

	/**
	 * @return LoggerInterface
	 */
	public function getLogger()
	{
		return $this->logger;
	}
}

?>