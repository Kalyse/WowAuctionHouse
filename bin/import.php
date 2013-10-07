<?php
date_default_timezone_set('Europe/Berlin');

require_once __DIR__.'/../Application.php';

$wow = new Wow();
$downloader = new AuctionHouse_Downloader( $wow, new Zend_Http_Client());
Application::getInstance()->getDB()->registerLogger(new Logger(new Logger_Logfile("/tmp/sql.log")));
$i = new AuctionHouse_Importer($wow, $downloader, Application::getInstance()->getDB());
$data = $i->execute( 'eu', 'Teldrassil');
$data = $i->execute( 'eu', 'Forscherliga');
//echo $data;
