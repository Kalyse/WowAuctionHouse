<?php

/**
 * Index Controller: display some items
 *
 */
class Controller_Index extends Controller_Abstract {
	public function indexAction()
	{
		$db = Application::DB();
		$res = $db->query("SELECT region,realm,house,itemid,
				SUM(quantity) AS Volume,
				MIN(buyout/quantity) AS MinPreis,
				MAX(buyout/quantity) AS MaxPreis,
				SUM(buyout/quantity) AS Umsatz,
				STDDEV(buyout/quantity) AS Stdev,
				SUM(buyout/quantity)/COUNT(a.id) AS Schnitt,
				item.link,item.name

			FROM auctions a
			LEFT JOIN item ON (item.id=a.itemid)
			WHERE STATUS='buy'
				AND house='alliance' ".
				($this->getRequestVar('realm') ? "AND realm=".$db->quote($this->getRequestVar('realm')) : "")."
				AND lastseen>".$db->quote(date("Y-m-d H:i:s", strtotime(($this->getRequestVar('days') ? (int)$this->getRequestVar('days') : "7")." days ago")))."

			GROUP BY region,realm,house,itemid
			HAVING `Volume`>".($this->getRequestVar('minquant') ? $db->quote($this->getRequestVar('minquant')) : "10")."
			ORDER BY SUM(quantity) * SUM(buyout/quantity) / STDDEV(buyout/quantity) DESC LIMIT 100");
		$data = array();
		while(is_object($res) && $row = $res->fetch()) {
			$data[] = $row;
		}
		$this->assign("data", $data);
	}
}

?>