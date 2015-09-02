<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
require('./global.php');
require(CUR_CONF_PATH."lib/push_notify.class.php");
class push extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function index()
	{
		
	}
	function detail()
	{
		
	}
	function count()
	{
		
	}
	public function show()
	{
		$message = $this->input['message'];
		if (!$message)
		{
			$this->errorOutput(NO_PUSH_MESSAGE);
		}
		$config = array(
			'publish' => $this->input['publish'],	
			'badge' => $this->input['badge'],	
			'sound' => $this->input['sound'],	
			'type' => $this->input['type'],	
		);
		$pushNotify = new pushNotify($config);
		$pushNotify->connectToAPNS();
		$sql = 'SELECT * FROM ' . DB_PREFIX . "device WHERE enabled = 1";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$ret = $pushNotify->send($r['device_token'], $message);
			$this->addItem(array('deviceToken' => $r['device_token'], 'ret' => $ret));
		}
		
		$pushNotify->closeConnections();
		$this->output();
	}
}

$out = new push();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>