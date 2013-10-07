<?php

require_once ('lib/Interfaces/WowItemClientInterface.php');

/**
 * Import item data from wowhead
 */
class Item_Importer_Wowhead implements WowItemClientInterface
{
	/**
	 * @var Item
	 */
	protected $_item;
	protected $_http;

	public function __construct(Item $item, Zend_Http_Client $http)
	{
		$this->_item = $item;
		$this->_http = $http;
	}


	public function import($itemId)
	{
		$this->_item->initById($itemId);
		$this->_http->setUri("http://de.wowhead.com/item=".$itemId."&xml");
		$response = $this->_http->request();
		$body = $response->getBody();
		try {
			$this->parse($body);
		} catch (Exception $e) {
			throw new ItemImportException("Error importing item $itemId", 1, $e);
		}
		return $this->_item->save();
	}

	public function parse($strData)
	{
		$dom = new DOMDocument();
		if(!$dom->loadXML($strData))
		{
			throw new ItemImportException("Non-wellformed XML from wowhead");
		}
		$items = $dom->getElementsByTagName('item');
		if(!$items) {
			throw new ItemImportException("Non-wellformed XML from wowhead");
		}
		$item = $items->item(0);
		$xp = new DOMXPath($dom);
		$el = $xp->query('/wowhead/item');
		//$this->_item->id = $item->getAttribute('id');
		$this->_item->name = $item->getElementsByTagName('name')->item(0)->textContent;
		$this->_item->level = $item->getElementsByTagName('level')->item(0)->textContent;
		$this->_item->quality = $item->getElementsByTagName('quality')->item(0)->getAttribute('id');
		$this->_item->class = $item->getElementsByTagName('class')->item(0)->getAttribute('id');
		$this->_item->subclass = $item->getElementsByTagName('subclass')->item(0)->getAttribute('id');
		$this->_item->iconid = $item->getElementsByTagName('icon')->item(0)->getAttribute('displayId');
		$this->_item->htmltooltip = $item->getElementsByTagName('htmlTooltip')->item(0)->textContent;
		$this->_item->link = $item->getElementsByTagName('link')->item(0)->textContent;
	}
}

?>