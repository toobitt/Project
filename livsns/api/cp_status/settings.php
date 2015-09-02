<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: settings.php 6437 2012-04-17 07:00:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID', 'mblog_settings_m'); //模块标识
class settingsApi extends outerReadBase
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
		$sql = "SELECT * FROM " . DB_PREFIX . "settings ";
		$q = $this->db->query($sql);
		$info = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$this->addItem($row);
		}
		$this->output();
	}

	public function getMark()
	{
		if(empty($this->input['mark']))
		{
			$this->errorOutput('未传入标识');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "settings WHERE mark='" . urldecode($this->input['mark']) . "'";
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('无内容');
		}
		else
		{
			$this->addItem($f);
			$this->output();	
		}
	}

	public function detail()
	{
		$order = '';
		if(empty($this->input['id']))
		{
			$order = ' LIMIT 1';
		}
		else
		{
			$order = ' where id=' . $this->input['id'];
		}
		$sql = "select * from " . DB_PREFIX . "settings " . $order;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('无内容');
		}
		else
		{
			$this->addItem($f);
			$this->output();	
		}
	}
	
	public function count(){
		
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}
$out = new settingsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unkonw';
}
$out->$action();
?>