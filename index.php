<?php
require_once './Application.php';

error_reporting(E_ALL);
ini_set('display_errors', true);

$app =new MvcApplication(__DIR__);
//$app->registerLogger(new Logger( new Logger_Syslog("ah"), LoggerInterface::DEBUG));
$app->initDb();
$db = $app->getDB();
echo $app->run();

