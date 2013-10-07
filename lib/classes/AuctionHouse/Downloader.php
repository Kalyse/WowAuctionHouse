<?php

/**
 * Auction house Downloader
 *
 * @author TLamy
 *
 */
class AuctionHouse_Downloader {
	/**
	 * @var TLamy\Wow\Wow
	 */
	protected $_Wow;
	protected $_timestamp = null;

	public function __construct( $Wow)
	{
		$this->_Wow = $Wow;
	}

	/**
	 * Download Auction House data from BattleNet. If successful, $this->getTimestamp
	 * returns the timestamp of the downloaded file as privded by Blizzard
	 * @param string $region
	 * @param string $realm
	 * @param Zend_Http_Client $httpClient
	 * @throws Exception
	 * @return string		Auction Data in JSON notation
	 * @
	 */
	public function download( $region, $realm, Zend_Http_Client $httpClient)
	{
		$this->_timestamp = null;
		$apiData = $this->getApiUrl($region, $realm, $httpClient);
		if( !$apiData instanceof stdClass) {
			throw new Exception("No download data available");
		}
		$this->_timestamp = $apiData->lastModified / 1000;
		$httpClient->setUri($apiData->url);

		$response = $httpClient->request();
		return $response->getBody();
	}

	public function getTimestamp()
	{
		return $this->_timestamp;
	}

	/**
	 * Get download URL and timestamp for auction data
	 * @param string $region
	 * @param string $realm
	 * @param Zend_Http_Client $httpClient
	 * @return stdClass | null
	 * @example { "url": "http://eu.battle.net/auction-data/bff63465044448bcd8cce3aceb5888af/auctions.json", "lastModified": 1360523979000 }
	 */
	public function getApiUrl( $region, $realm, Zend_Http_Client $httpClient)
	{
		$hostname = $this->_Wow->getBnetHostname($region);
		$url = "https://".$hostname."/api/wow/auction/data/".strtolower($realm);
		$httpClient->setUri($url);
		$response = $httpClient->request();
		$data = $response->getBody();
		$dataObj = json_decode($data);
		/* Original file format:
		 * { "files":
		 * 		[
		 *			{
		 *				"url": "http://eu.battle.net/auction-data/bff63465044448bcd8cce3aceb5888af/auctions.json",
		 *				"lastModified": 1360523979000
		 *			}
		 *		]
		 *	}
		 */
		if( $dataObj instanceof stdClass && property_exists($dataObj, 'files')) {
			return $dataObj->files[0];
		}
		return null;
	}
}

?>