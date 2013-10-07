<?php

require_once 'PHPUnit\Framework\TestSuite.php';

require_once 'Tests\ApplicationTest.php';

require_once 'Tests\AuctionHouse_DownloaderTest.php';

require_once 'Tests\AuctionHouse_ImporterTest.php';

require_once 'Tests\WowTest.php';

/**
 * Static test suite.
 */
class LibTestsSuite extends PHPUnit_Framework_TestSuite {
	
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'LibTestsSuite' );
		
		$this->addTestSuite ( 'ApplicationTest' );
		
		$this->addTestSuite ( 'AuctionHouse_DownloaderTest' );
		
		$this->addTestSuite ( 'AuctionHouse_ImporterTest' );
		
		$this->addTestSuite ( 'WowTest' );
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ();
	}
}

