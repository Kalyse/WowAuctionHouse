<?php

require_once 'lib/classes/Logger/Logfile.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Logger_Logfile test case.
 */
class Logger_LogfileTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Logger_Logfile
	 */
	private $Logger_Logfile;

	private $logfile;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->logfile = "/tmp/".getmypid();
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		// TODO Auto-generated Logger_LogfileTest::tearDown()
		$this->Logger_Logfile = null;

		parent::tearDown();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
		// TODO Auto-generated constructor
	}

	/**
	 * Tests Logger_Logfile->__construct()
	 */
	public function test__construct()
	{
		$this->assertFileNotExists($this->logfile, "Logfile exists before instantiating");
		$this->Logger_Logfile = new Logger_Logfile($this->logfile, false);
		$this->assertFileExists($this->logfile, "Logfile exists after instantiating");
	}

	/**
	 * Tests Logger_Logfile->write()
	 */
	public function testWrite()
	{
		if( !$this->Logger_Logfile)
			$this->Logger_Logfile = new Logger_Logfile($this->logfile, false);

		$teststring = "Das ist ein automatischer Test ";
		$sizeBeforeLog = filesize( $this->logfile);
		$this->Logger_Logfile->write( $teststring);
		$this->Logger_Logfile = null;
		$sizeAfterLog = filesize( $this->logfile);
		$this->assertEquals(strlen($teststring)+1, $sizeAfterLog-$sizeBeforeLog, "Size difference of logfile ".$this->logfile);
		unlink($this->logfile);
	}
}

