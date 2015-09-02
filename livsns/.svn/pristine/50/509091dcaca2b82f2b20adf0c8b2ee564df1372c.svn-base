<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 12397 2012-10-11 06:01:46Z wangleyuan $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','gongjiao');
require(ROOT_PATH."global.php");
class realtime extends adminBase
{
	private $buscurl;
	private $curl8684;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$routeid = $this->input['routeid'];
		$segmentid = $this->input['segmentid'];
		$stationseq = $this->input['stationseq'];
		if (!$stationseq || !$segmentid || !$routeid)
		{
			$this->errorOutput(PARAMETERS_ERROR);
		}
		$this->buscurl = new curl($this->settings['busapi']['host'], $this->settings['busapi']['dir']);
		$this->buscurl->initPostData();
		$this->buscurl->addRequestData('routeid', $routeid);
		$this->buscurl->addRequestData('segmentid', $segmentid);
		$this->buscurl->addRequestData('stationseq', $stationseq);
		$this->buscurl->setReturnFormat('str');
		$ret = $this->buscurl->request('bus.php');
		$ret = str_replace(array("!diffgr:id", "!msdata:rowOrder", "!diffgr:hasChanges"), array('diffgrid', 'msdatarowOrder', 'diffgrhasChanges'), $ret);
		exit($ret);
	}
}
$out = new realtime();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>