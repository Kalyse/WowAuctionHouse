<?php
// alter table auctions add index missing (region,realm,status);
/**
 * Import AuctionHouse data into database
 *
 * @author TLamy
 */
class AuctionHouse_Importer {
	const UPDATE_INTERVAL = 3600;
	protected $_wow;
	protected $_downloader;
	/**
	 * @var DB
	 */
	protected $_db;

	protected $chars = array("Mussnix","Bigt","Paulaner","Franziskaner","Bronzekrone","Brabbelfux","Arkanhund");

	public function __construct( Wow $wow, AuctionHouse_Downloader $downloader, DB $db)
	{
		$this->_wow = $wow;
		$this->_downloader = $downloader;
		$this->_db = $db;
	}


	public function execute( $region, $realm)
	{
		$aucIds = array();
		$data = $this->_downloader->download($region, $realm, new Zend_Http_Client());
		$timestamp = $this->_downloader->getTimestamp();
		echo "$region/$realm: Data as of ".date("Y-m-d H:i:s", $timestamp)."  (".(time()-$timestamp)." seconds ago)\n";
		$auctions = json_decode($data);
		$select = $this->_db->prepare( "SELECT * FROM auctions WHERE id=?");
		$insert = $this->_db->prepare("INSERT INTO auctions (region,realm,house,id,itemid,owner,bid,buyout,quantity,timeleft,firstseen,lastseen) ".
				"VALUES (".$this->_db->quote($region).",".$this->_db->quote($realm).
				",:house,:auc,:item,:owner,:bid,:buyout,:quantity,:timeLeft,".
				$this->_db->quote(date("Y-m-d H:i:s", $timestamp)).",".
				$this->_db->quote(date("Y-m-d H:i:s", $timestamp)).
				")");

		foreach(array('alliance','horde','neutral') as $auctionRealm) {
			foreach( $auctions->$auctionRealm->auctions as $auction) {
				$aucIds[] = $auction->auc;
				if( !$select->execute(array($auction->auc))) {
					throw new Exception("Execute failed: ".$select->debugDumpParams());
				}
				$rows = $select->rowCount();
				$aucData = $select->fetch(PDO::FETCH_ASSOC);
				if( $aucData['id'] == $auction->auc) {
					if( $aucData['timeleft'] != $auction->timeLeft) {
						$endtime = $this->guessEndtime($auction->timeLeft, $timestamp);
						$this->_db->exec("UPDATE auctions
								SET status=NULL,
								timeleft=".$this->_db->quote($auction->timeLeft).",
								lastseen=".$this->_db->quote(date('Y-m-d H:i:s', $timestamp))."
								WHERE id=".$this->_db->quote($auction->auc, PDO::PARAM_INT));
					} elseif(array_key_exists('status', $aucData) && $aucData['status'] != '') {
						$this->_db->exec("UPDATE auctions SET status=NULL WHERE id=".$this->_db->quote($auction->auc, PDO::PARAM_INT));
					}
				} else {
					$bind = $this->_db->getBindFromObject( $auction, array('auc', 'item', 'owner', 'bid', 'buyout', 'quantity', 'timeLeft', 'house'));
					$bind[':house'] = $auctionRealm;

					if( !$insert->execute($bind))
					{
						die("Insert failed for ".$insert->debugDumpParams()."\n".json_encode($bind)."\n".$this->_db->errorInfo()[0]."\n".json_encode($auction));
					}
					$endtime = $this->guessEndtime($auction->timeLeft, $timestamp);
					$this->_db->exec("UPDATE auctions SET endtime=".$this->_db->quote($endtime)." WHERE id=".$this->_db->quote($auction->auc, PDO::PARAM_INT));
				}
			}
		}
		$this->endAuctions($aucIds, $region, $realm, $timestamp);
	}

	public function endAuctions($activeAuctions, $region, $realm, $dataTimestamp)
	{
		$reports = array();
		echo "Updating status\n";
		if( empty( $activeAuctions)) {
			echo "No active auctions?\n";
			return '';
		}
		$updates = array();
		$ended = $this->_db->query( "SELECT * FROM auctions WHERE status IS NULL
				AND region=".$this->_db->quote($region)." AND realm=".$this->_db->quote($realm)."
				AND id NOT IN (".join(',', $activeAuctions).")");
		while( false != ($auc = $ended->fetch(PDO::FETCH_ASSOC))) {
			$end = strtotime($auc['endtime']) - self::UPDATE_INTERVAL;
			$diff = $end - $dataTimestamp;
			$upData = array();
			echo "{$auc['id']}  $diff seconds remaining ";
			if ($diff < 0) {
				echo "-> timeout\n";
				$upData['status'] = "timeout";
			} else {
				echo "-> bought\n";
				$upData['status'] = "buy";
			}
			if( !empty($upData)) {
				$updates[$auc['id']] = $upData;
			}
			if( in_array($auc['owner'], $this->chars))
			{
				$reports[] = array_merge($auc, $upData);
			}
		}
		foreach( $updates as $id=>$data) {
			$up = array();
			foreach( $data as $key=>$val)
			{
				$up[] = "$key=".$this->_db->quote($val);
			}
			$qry = "UPDATE auctions SET ".implode(",", $up)." WHERE id=".$this->_db->quote($id);
			$this->_db->query($qry);
		}
		return $this->report($reports);
	}


	public function guessEndtime($newTimeleft, $importTimestamp)
	{
		$secondsLeftNow = date("Y-m-d H:i:s", $importTimestamp+$this->getSecondsLeft($newTimeleft));
		return $secondsLeftNow;
	}


	public function getSecondsLeft($timeLeft)
	{
		$secondsLeft = null;
		switch ($timeLeft) {
			case "VERY_LONG":
				$secondsLeft = 48*3600;
				break;
			case "LONG":
				$secondsLeft = 12*3600;
				break;
			case "MEDIUM":
				$secondsLeft = 2*3600;
				break;
			case "SHORT":
				$secondsLeft = 0.5*3600;
				break;
			default:
				throw new Exception("Unknown TimeLeft value: $timeLeft");
		}
		return $secondsLeft-self::UPDATE_INTERVAL;
	}

	protected function report($reports)
	{
		if(empty($reports)) {
			return;
		}
		$soldMoney = 0;
		$endMoney = 0;
		$items = array();
		foreach($reports as $report)
		{
			if( !is_array($items[$report['itemid']])) {
				$items[$report['itemid']] = array();
			}
			$items[$report['itemid']][] = $report['id'];
			if( $report['status'] == "buy") {
				$soldMoney += $report['buyout']/10000;
			} else {
				$endMoney  += $report['buyout']/10000;
			}
		}
		foreach($items as $itemId=>$auctionIds) {
			$res = $this->_db->query("SELECT name FROM item WHERE id=".$itemId);
			$items[$itemId] = $res->fetchColumn();
		}
		$email = array(sprintf("%0.2fG verkauft, %0.2fG beendet.\n\n", $soldMoney, $endMoney));
		foreach($reports as $report) {
			$email[] = sprintf("%16s | %3d x %-30s | %5.2f | %s   id=%d",
					$report['owner'], $report['quantity'], $items[$report['itemid']],
					$report['buyout']/10000, $report['status'], $report['id']);
		}
		return mail("wow@lamy.cytainment.de", "WoW Auction Status (".php_uname('n').")", implode("\n", $email), "Content-Type: text/plain; charset=UTF-8");
	}
}

?>
