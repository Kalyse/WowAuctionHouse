<?php

/**
 * Table gateway
 *
 * TODO: Change "_" variable prefixes to something else
 */
class DBRecord
{
	/**
	 * @var DB
	 */
	protected $_db;

	protected $_table;
	protected $_cols = array();
	/**
	 * Primary Key column name
	 * @var string
	 */
	protected $_pk = 'id';
	protected $_vars = array();
	protected $_changed = array();
	protected $_loaded = false;

	private $_selectStmt;

	/**
	 *
	 */
	public function __construct(DBInterface $db, $id=null)
	{
		$this->_db = $db;
		$this->_selectStmt = $this->_db->prepare("SELECT * FROM `".$this->_table."` WHERE `".$this->_pk."`=?");
		if($id) {
			$this->load($id);
		}
	}

	public function load($pkVal)
	{
		$this->reset();
		$this->_vars[$this->_pk] = $pkVal;
		$this->_loaded = false;
		if(!$this->_selectStmt->execute(array($pkVal))) {
			throw new Exception("Failed to load ".$this->_table." #".$pkVal);
		}
		if(false === ($row = $this->_selectStmt->fetch(PDO::FETCH_ASSOC))) {
			return false;
		}
		$this->_vars[$this->_pk] = $row[$this->_pk];
		foreach ($this->_cols as $col) {
			if (array_key_exists($col, $row)) {
				$this->_vars[$col] = $row[$col];
			} else {
				$this->_vars[$col] = null;
			}
		}
		$this->_loaded = true;
		return true;
	}

	private function reset()
	{
		$this->_vars = array_fill_keys( array_merge(array($this->_pk), $this->_cols), null);
		$this->_changed = array();
	}

	public function isLoaded()
	{
		return $this->_loaded;
	}


	public function __set($key, $value)
	{
		if(!in_array($key, $this->_cols)) {
			throw new Exception("Unable to set '$key'");
		}
		if($this->_vars[$key] != $value) {
			$this->_vars[$key] = $value;
			$this->_changed[$key] = true;
		}
	}

	public function __get($key)
	{
		if(!array_key_exists($key, $this->_vars) && $key != $this->_pk) {
			throw new Exception("Unable to get '$key'");
		}
		return $this->_vars[$key];
	}

	public function __isset($key)
	{
		return array_key_exists($key, $this->_vars);
	}

	public function save()
	{
		if(!$this->isLoaded())
		{
			return $this->insert();
		}
		if(!empty($this->_changed)) {
			$upData = array();
			foreach(array_keys($this->_changed) as $col) {
				$upData[] = "`".$col."`=".$this->_db->quote($this->_vars[$col]);
			}
			$sql = "UPDATE `".$this->_table."` SET ".implode(",", $upData)." WHERE `".$this->_pk."`=".$this->_db->quote($this->_vars[$this->_pk]);
			echo "$sql\n";
			return $this->_db->exec($sql);
		}
		return null;
	}

	public function insert()
	{
		if(!empty($this->_changed)) {
			$data = array('`'.$this->_pk.'`'=>$this->_db->quote($this->_vars[$this->_pk]));

			foreach(array_keys($this->_changed) as $col) {
				$data['`'.$col.'`'] = $this->_db->quote($this->_vars[$col]);
			}
			$sql = "INSERT INTO `".$this->_table."` (".implode(",", array_keys($data)).") VALUES (".implode(",", $data).")";
			echo $sql."\n";
			$res = $this->_db->exec($sql);
			return $res;
		}
		return null;
	}
}

?>