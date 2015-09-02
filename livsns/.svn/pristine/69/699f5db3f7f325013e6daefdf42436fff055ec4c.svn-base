<?php

define('MOD_UNIQUEID','push_feedback');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/PushFeedBack.class.php");
define('SCRIPT_NAME', 'PushFeedBackPlan');
class PushFeedBackPlan extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '推送反馈',	 
			'brief' => '获取应用下无效设备标识列表',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//查询所有app的发布版证书
		$sql = "SELECT appid,apply  FROM " .DB_PREFIX. "certificate WHERE send_way = 1";
		$q = $this->db->query($sql);
		$appinfo = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['apply'])
			{
				$appinfo[$r['appid']] = $r['apply'];
			}
		}
		
		if(empty($appinfo))
		{
			return FALSE;
		}
		
		$pushFeedBack = new PushFeedBack();
			
		$pushFeedBack->SetFeedBackHost();
		
		$data = array();
		foreach ($appinfo as $k => $v)
		{
			//传递证书
			$pushFeedBack->SetCert(ZS_DIR . $v);
			$ret = $pushFeedBack->ConnectToFeedBack();
			if(!$ret)
			{
				continue;
			}
			else 
			{
				$data[$k] = $ret;
			}
		}
		
		//连接反馈服务器
		$pushFeedBack->CloseConnections();
		
		if(empty($data))
		{
			return FALSE;
		}
		
		//反馈时间后设备没有重新注册，将状态置为2
		foreach ($data as $key => $val)
		{
			foreach ($val as $k => $v)
			{
				$sql = "SELECT update_time FROM " . DB_PREFIX . "device 
						WHERE appid = " . $key . " AND device_token = '" . $v['fb_devi'] . "' LIMIT 0,1";
				$res = $this->db->query_first($sql);
				if($res['update_time'] && $res['update_time'] <= $v['fb_time'])
				{
					$sql = "UPDATE " . DB_PREFIX . "device SET state = 2 WHERE appid = ".$key." AND device_token = '" . $v['fb_devi'] . "'";
					$this->db->query($sql);
				}
			}
		}
		
		$this->addItem('success');
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>