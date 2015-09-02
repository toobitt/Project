<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 1413 2010-12-30 01:01:24Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require('../global.php');
//require('./socket.class.php');
class index extends uiBaseFrm
{
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
		$this->ConnectQueue();
		$stats = $this->queue->getStats();
		echo '<pre>';
		print_r($stats);
		echo '</pre>';
	}

}
$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>