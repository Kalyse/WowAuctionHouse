<?php
require_once __DIR__.'/../Application.php';

$app = new Application(__DIR__.'/..');
$app->initDb();

if(false === ($r = $app->getDB()->query(
		"SELECT DISTINCT itemid FROM auctions A
		LEFT JOIN item I ON (I.id=A.itemid)
		where I.mtime IS NULL"))) {
	throw new Exception("doof! ".$app->getDB()->errorInfo()[0]);
}


$idlist = $r->fetchAll(PDO::FETCH_COLUMN, 0);
//$idlist = array(41381);
$imp = new Item_Importer_Wowhead(new Item($app->getDB()), new Zend_Http_Client());

foreach($idlist as $id) {
	echo "id=$id\n";
	try {
		$imp->import($id);
	} catch (ItemImportException $e) {
		echo $e->getMessage()."\n";
	}
}
//echo $data;
