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
class settingsApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	
	public function create()
	{	
		if(empty($this->input['name']))
		{
			$this->errorOutput('未传入名称！');
		}
		if(empty($this->input['mark']))
		{
			$this->errorOutput('未传入标识！');
		}

		$info = array(
			'name' => urldecode($this->input['name']),	
			'mark' => urldecode($this->input['mark']),	
			'state' => 0,	
			'last_time' => TIMENOW,	
			'ip' => hg_getip(),	
		);
		$sql = "INSERT INTO " . DB_PREFIX . "settings SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$this->addItem($info);
		$this->output();	
	}

	public function update()
	{	
		if(empty($this->input['id']))
		{
			$this->errorOutput('未传入ID');
		}

		$info = array(
			'name' => urldecode($this->input['name']) ? urldecode($this->input['name']) : '',
			'mark' => urldecode($this->input['mark']) ? urldecode($this->input['mark']) : '',
			'last_time' => TIMENOW,	
		);
		$sql = "UPDATE " . DB_PREFIX . "settings SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			if(!empty($v))
			{
				$sql .= $space . $k . "='" . $v . "'";
				$space = ',';
			}
		}
		$info['id'] = $this->input['id'];
		$sql .= ' WHERE id=' . $info['id'];
		$this->db->query($sql);
		$this->addItem($info);
		$this->output();	
	}

	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('未传入ID');
		}
		$sql = "delete from " . DB_PREFIX . "settings WHERE id=" . $this->input['id'];
		$this->db->query($sql);
		$this->addItem(array('id' => $this->input['id']));
		$this->output();	
	}

	public function audit()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('未传入ID');
		}
		$sql = "UPDATE " . DB_PREFIX . "settings SET state=" . ($this->input['state'] ? 1 : 0) . " WHERE id=" . $this->input['id'];
		$this->db->query($sql);
		$this->addItem(array('id' => $this->input['id'],'state' => ($this->input['state'] ? 1:0)));
		$this->output();	
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