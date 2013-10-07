<?php

require_once 'lib/classes/AuctionHouse/Downloader.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * AuctionHouse_Downloader test case.
 */
class AuctionHouse_DownloaderTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var AuctionHouse_Downloader
	 */
	private $AuctionHouse_Downloader;
	private $mockWow;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();

		$this->mockWow = $this->getMock("Wow", array('getBnetHostname'));
		$this->mockWow->expects($this->any())->method('getBnetHostname')->will($this->returnValue('eu.battle.net'));
		$this->AuctionHouse_Downloader = new AuctionHouse_Downloader($this->mockWow);
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated AuctionHouse_DownloaderTest::tearDown()
		$this->AuctionHouse_Downloader = null;

		parent::tearDown ();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	public function testGetApiUrl()
	{
		$resultMock = $this->getMock('Zend_Http_Response', array('getBody'));
		$resultMock->expects($this->any())
				->method('getBody')
				->with()
				->will($this->returnValue('{"files": [ {"url": "testurl","lastModified": 1360590760000 } ] }'));

		$httpMock = $this->getMock('Zend_Http_Client', array('setURI', 'request'));
		$httpMock->expects($this->any())
				->method('setUri')
				->with($this->equalTo('https://eu.battle.net/api/wow/auction/data/teldrassil'))
				->will($this->returnValue(null));
		$httpMock->expects($this->any())
				->method('request')
				->will($this->returnValue($resultMock));

		$this->assertNull($this->AuctionHouse_Downloader->getTimestamp());
		$result = $this->AuctionHouse_Downloader->getApiUrl("eu","teldrassil", $httpMock);

		$expectedResult = new stdClass();
		$expectedResult->url = 'testurl';
		$expectedResult->lastModified = 1360590760000;
		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * Tests AuctionHouse_Downloader->download()
	 */
	public function testDownload() {
		$resultMock = $this->getMock('Zend_Http_Response', array('getBody'));
		$resultMock->expects($this->any())
				->method('getBody')
				->with()
				->will($this->returnValue('{"files": [ {"url": "https://eu.battle.net/api/wow/auction/data/teldrassil","lastModified": 1360590760000 } ] }'));

		$httpMock = $this->getMock('Zend_Http_Client', array('setURI', 'request'));
		$httpMock->expects($this->any())
				->method('setUri')
				->with($this->equalTo('https://eu.battle.net/api/wow/auction/data/teldrassil'))
				->will($this->returnValue(null));
		$httpMock->expects($this->any())
				->method('request')
				->will($this->returnValue($resultMock));
		$this->assertEquals('{"files": [ {"url": "https://eu.battle.net/api/wow/auction/data/teldrassil","lastModified": 1360590760000 } ] }', $this->AuctionHouse_Downloader->download("eu","teldrassil", $httpMock));
	}


}

