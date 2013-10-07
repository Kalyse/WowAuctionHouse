<?php

require_once 'lib/classes/Application.php';
require_once 'lib/Interfaces/DBInterface.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Application test case.
 */
class ApplicationTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Application
	 */
	private $Application;
	/**
	 * @var DBInterface
	 */
	private $mockDB;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Application = new Application(__DIR__."/../", "Tests/config.ini");
		$this->mockDB = $this->getMock("DB", array('__construct',), array(), 'MockDB', false);
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Application = null;
		parent::tearDown ();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}

	/**
	 * Tests Application->__construct()
	 */
	public function test__construct() {
		$this->Application->__construct(__DIR__."/../");
		$this->assertNull( $this->Application->getConfig("something", null));
	}

	/**
	 * Tests Application->initDb()
	 */
	public function testInitDb() {
		$db = $this->Application->initDb('MockDB');
		$this->assertInstanceOf("MockDB", $db);
	}

	/**
	 * Tests Application::DB()
	 */
	public function testDB() {
		$expectedDB = $this->Application->initDb('MockDB');

		$this->assertEquals($expectedDB, $this->Application->DB());
	}

	/**
	 * Tests Application->getDB()
	 */
	public function testGetDB() {
		$expectedDB = $this->Application->initDb('MockDB');

		$this->assertEquals($expectedDB, $this->Application->getDB());
	}

	/**
	 * Tests Application->getConfig()
	 */
	public function testGetConfig() {
		// TODO Auto-generated ApplicationTest->testGetConfig()
		$this->markTestIncomplete ( "getConfig test not implemented" );

		$this->Application->getConfig(/* parameters */);
	}

	/**
	 * Tests Application->readConfig()
	 */
	public function testReadConfig() {
		// TODO Auto-generated ApplicationTest->testReadConfig()
		$this->markTestIncomplete ( "readConfig test not implemented" );

		$this->Application->readConfig(/* parameters */);
	}
}

