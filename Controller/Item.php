<?php

/**
 * Item Controller
 *
 */
class Controller_Item extends Controller_Abstract
{
	public function indexAction()
	{
		throw new Exception_404();
	}

	public function statisticsAction()
	{
		$db = Application::DB();
		$item = new Item($db);
		$item->initById($this->getRequestVar('item'));
		$this->assign("item", $item);
		$this->assign("region", $this->getRequestVar('region'));
		$this->assign("realm", $this->getRequestVar('realm'));
		$this->assign("house", $this->getRequestVar('house'));
	}


	public function chartAction()
	{
		require_once 'lib/vendor/jpgraph/jpgraph.php';
		require_once 'lib/vendor/jpgraph/jpgraph_error.php';
		require_once 'lib/vendor/jpgraph/jpgraph_line.php';
		require_once 'lib/vendor/jpgraph/jpgraph_bar.php';
		$db = Application::DB();

		$item = new Item($db);
		$item->initById($this->getRequestVar('item'));

		$res = $db->query("SELECT region,realm,house,itemid,
				SUM(quantity) AS Volume,
				MIN(buyout/quantity) AS MinPreis,
				MAX(buyout/quantity) AS MaxPreis,
				SUM(buyout/quantity) AS Umsatz,
				STDDEV(buyout/quantity) AS Stdev,
				SUM(buyout/quantity)/COUNT(a.id) AS Schnitt,
				item.link,item.name,
				date_format(lastseen, '%a %m-%d') as Datum

			FROM auctions a
			LEFT JOIN item ON (item.id=a.itemid)
			WHERE STATUS='buy'
				AND itemid=".$db->quote($this->getRequestVar('item'))."
				AND region=".$db->quote($this->getRequestVar('region'))."
				AND realm=".$db->quote($this->getRequestVar('realm'))."
				AND house=".$db->quote($this->getRequestVar('house'))."
				AND lastseen>".$db->quote(date("Y-m-d H:i:s", strtotime("14 days ago")))."
			GROUP BY date_format(lastseen, '%Y-%m-%d')");
		$data = array();
		$minMaxData = array();
		$xData = array();
		$schnittData = array();
		$volumeData = array();
		while(is_object($res) && $row = $res->fetch()) {
			$data[] = $row;
			$volumeData[] = $row['Volume'];
			$minMaxData[] = $row['MinPreis']/10000;
			$minMaxData[] = $row['MaxPreis']/10000;
			$xData[] = $row['Datum'];
			$schnittData[] = $row['Schnitt']/10000;
		}
		Application::getInstance()->getLogger()->info("MinMaxData: ".json_encode($minMaxData));
		Application::getInstance()->getLogger()->info("xData: ".json_encode($xData));
		$graph1 = new Graph(800, 350, "auto");
		$graph1->img->SetMargin(40,50,20,70);
		$graph1->SetScale("textlin");
		$graph1->SetY2Scale('lin');
		$graph1->title->Set("Min/Max Preis");
		$graph1->xaxis->title->Set("Datum");
		$graph1->xaxis->SetLabelAngle(90);
		$graph1->yaxis->title->Set("Preis");
		$graph1->y2axis->title->Set("Anzahl");
		$graph1->y2axis->title->SetMargin(50);
		$plot = new ErrorPlot($minMaxData);
		$graph1->Add($plot);
		$plot = new LinePlot($schnittData);
		$plot->SetLineWeight(2);
		$plot->SetColor("indigodye");
		$graph1->Add($plot);
		$plotVol = new BarPlot($volumeData);
		$plotVol->SetColor('green');
		$graph1->AddY2($plotVol);
		//$graph1->ynaxis[0]->SetColor('green');
		$graph1->xaxis->setTickLabels($xData);

		ob_start();
		$graph1->Stroke();
		$img = ob_get_clean();
		$this->enableLayout(false);
		$this->enableView(false);
		return $img;
	}
}

?>