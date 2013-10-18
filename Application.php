<?php
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();
//spl_autoload_register("MyAutoloader");
//set_include_path(__DIR__.'/lib/classes/:'.get_include_path());

// Autoload Zend classes
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->pushAutoloader("appAutoloader");

function appAutoloader( $class)
{
	$includePath = __DIR__.'/lib/mvc/:'.
			__DIR__.'/lib/classes/:'.
			__DIR__."/:".
			__DIR__."/Model/:".
			__DIR__."/lib/vendor/";
			/*":".implode(":", glob(__DIR__."/lib/vendor/*", GLOB_ONLYDIR))*/
	//echo "Look for $class in $includePath<br/>\n";
	$classNoNamespace = basename( str_replace( '\\', '/', $class));
	$classPath = str_replace('_', '/', $classNoNamespace);

	foreach( explode( ':', $includePath) as $basePath) {
		if( file_exists($basePath.$classPath.'.php')) {
			require_once $basePath.$classPath.'.php';
			break;
		} elseif( file_exists($basePath.$classNoNamespace.'.php')) {
			require_once $basePath.$classNoNamespace.'.php';
			break;
		}
	}
}

$Application = new Application(__DIR__);
$Application->initPhp();
$Application->initDb();
$Application->registerLogger(new Logger( new Logger_Logfile("/tmp/auctionhouse.log"), LoggerInterface::DEBUG, $Application->getConfig('logging')));
$Application->getLogger()->debug(json_encode($Application->getConfig('logging')));
?>