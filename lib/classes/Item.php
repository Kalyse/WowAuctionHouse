<?php

require_once ('lib/classes/DB/Item.php');

/**
 * @author lamy
 *
 */
class Item extends DB_Item
{

	/**
   *
	*/
	function __construct(DBInterface $db)
	{
		parent::__construct($db);
	}
}

class ItemImportException extends Exception {}
?>