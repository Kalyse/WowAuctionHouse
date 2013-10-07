<?php

require_once 'lib\classes\AuctionHouse\Importer.php';

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * AuctionHouse_Importer test case.
 */
class AuctionHouse_ImporterTest extends PHPUnit_Framework_TestCase {
	
	/**
	 *
	 * @var AuctionHouse_Importer
	 */
	private $AuctionHouse_Importer;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated AuctionHouse_ImporterTest::setUp()
		
		$this->AuctionHouse_Importer = new AuctionHouse_Importer(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated AuctionHouse_ImporterTest::tearDown()
		$this->AuctionHouse_Importer = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests AuctionHouse_Importer->__construct()
	 */
	public function test__construct() {
		// TODO Auto-generated AuctionHouse_ImporterTest->test__construct()
		$this->markTestIncomplete ( "__construct test not implemented" );
		
		$this->AuctionHouse_Importer->__construct(/* parameters */);
	}
	
	/**
	 * Tests AuctionHouse_Importer->execute()
	 */
	public function testExecute() {
		// TODO Auto-generated AuctionHouse_ImporterTest->testExecute()
		$this->markTestIncomplete ( "execute test not implemented" );
		
		$this->AuctionHouse_Importer->execute(/* parameters */);
	}
}

