<?php
/**
 * Import an WoW item from external source
 *
 */
interface Item_Importer_Interface
{
	/**
	 * Import item with specified itemid
	 *
	 * @param int $itemId
	 * @throws ItemImportException
	 * @return boolean
	 */
	public function import($itemId);

}