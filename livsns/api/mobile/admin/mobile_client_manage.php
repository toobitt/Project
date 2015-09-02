<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','mobile_client_manage');
require('./global.php');
class ClientManage extends adminReadBase
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
	public function show()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$condition = $this->get_condition();
		
		$order_by = ' ORDER BY amount DESC ';
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'client WHERE 1 '.$condition. $order_by . $limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			
			if($r['create_time'])
			{
				$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			}
			
			$this->addItem($r);
		}
		$this->output();
	}
	function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."client  WHERE 1 ".$condition;
		echo json_encode($this->db->query_first($sql));
	}
	
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND client_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//应用id
		if($this->input['id'])
		{
			$condition .= ' AND id='.intval($this->input['id']);
		}
		
		return $condition;
	}
	
	function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "client WHERE id IN (" . $id . ")";
		if (!$this->db->query($sql))
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
}

$out = new ClientManage();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>