<?php

require_once ('lib/classes/DB/Item.php');

/**
 * Auctions.item
 *
 * @author lamy
 */
class Item extends DB_Item
{

	/**
	 *
	 */
	public function __construct(DBInterface $db)
	{
		parent::__construct($db);
	}
}

class ItemImportException extends Exception {}
?>