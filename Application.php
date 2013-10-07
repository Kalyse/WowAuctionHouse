<?php
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();
//spl_autoload_register("MyAutoloader");
//set_include_path(__DIR__.'/lib/classes/:'.get_include_path());

// Autoload Zend classes
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->pushAutoloader("MyAutoloader");

function MyAutoloader( $class)
{
	$includePath = __DIR__.'/lib/mvc/:'.
			__DIR__.'/lib/classes/:'.
			__DIR__."/:".
			__DIR__."/lib/vendor/";
			/*":".implode(":", glob(__DIR__."/lib/vendor/*", GLOB_ONLYDIR))*/
	//echo "Look for $class in $includePath<br/>\n";
	$classNoNamespace = basename( str_replace( '\\', '/', $class));
	$classPath = str_replace('_', '/', $classNoNamespace);

	foreach( explode( ':', $includePath) as $basePath) {
		if( file_exists($basePath.$classPath.'.php')) {
			include $basePath.$classPath.'.php';
			return;
		} elseif( file_exists($basePath.$classNoNamespace.'.php')) {
			include $basePath.$classNoNamespace.'.php';
			return;
		}
	}
	return;
}

date_default_timezone_set("Europe/Berlin");

$Application = new Application(__DIR__);
$Application->initDb();
$Application->registerLogger(new Logger( new Logger_Logfile("/tmp/auctionhouse.log"), LoggerInterface::DEBUG, $Application->getConfig('logging')));
$Application->getLogger()->debug(json_encode($Application->getConfig('logging')));
?>