<?php

require_once 'lib\classes\Wow.php';

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * Wow test case.
 */
class WowTest extends PHPUnit_Framework_TestCase {
	
	/**
	 *
	 * @var Wow
	 */
	private $Wow;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated WowTest::setUp()
		
		$this->Wow = new Wow(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated WowTest::tearDown()
		$this->Wow = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests Wow->getBnetHostname()
	 */
	public function testGetBnetHostname() {
		// TODO Auto-generated WowTest->testGetBnetHostname()
		$this->markTestIncomplete ( "getBnetHostname test not implemented" );
		
		$this->Wow->getBnetHostname(/* parameters */);
	}
}

