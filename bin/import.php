<?php
date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/../Application.php';

$wow = new Wow();
$downloader = new AuctionHouse_Downloader( $wow, new Zend_Http_Client());
Application::getInstance()->getDB()->registerLogger(new Logger(new Logger_Logfile("/tmp/sql.log")));
$i = new AuctionHouse_Importer($wow, $downloader, Application::getInstance()->getDB());

foreach([["eu","Forscherliga"],["eu","Teldrassil"]] as $realm) {
	try {
		$data = $i->execute( $realm[0], $realm[1]);
	} catch(Exception $ex) {
		echo $ex->getMessage()." for {$realm[1]}/{$realm[0]}\n";
	}
}
