<?php

/**
 * Gateway for table "item"
 *
 * @author lamy
 *
 * @property int	id
 * @property string	name
 * @property int	level
 * @property int	quality
 * @property int	subclass
 * @property int	iconid
 * @property string	htmltooltip
 * @property string	link
 * @property string	mtime
 */
class DB_Item extends DBRecord
{
	/**
	 * @var unknown_type
	 */
	protected $_table = "item";
	protected $_cols = array('name','level','quality','class','subclass','iconid','htmltooltip','link','mtime');
	protected $_pk = 'id';

	public function initById( $id)
	{
		return $this->load($id);
	}
}

?>