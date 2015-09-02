<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','client');
require(ROOT_PATH."global.php");
class client extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}
	public function detail()
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
		
		$order_by = ' ORDER BY a.update_time DESC ';
		
		$sql = 'SELECT a.*,b.device_name as types,c.device_os as system,d.client_name as program_name FROM ' . DB_PREFIX . 'device a
				LEFT JOIN '.DB_PREFIX.'device_library b
				ON a.types=b.id 
				LEFT JOIN '.DB_PREFIX.'device_os c 
				ON a.system=c.id 
				LEFT JOIN '.DB_PREFIX.'client d 
				ON a.program_name=d.id WHERE 1 ' . $condition . $order_by . $limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			//$r['device_token'] = str_replace(" ","_",$r['device_token']);
			$status = '';
			$status = $r['state'];
			if($r['update_time'])
			{
				$r['create_time'] = date('Y-m-d H:i',$r['update_time']);
			}
			if($status == 1)
			{
				$r['state'] = '正常';
			}
			else if($status == 2)
			{
				$r['state'] = '卸载';
			}
			else if($status == 3)
			{
				$r['state'] = '失活';
			}
			if($r['debug'])
			{
				$r['debug'] = '开发版';
			}
			else 
			{
				$r['debug'] = '发布版';
			}
			$this->addItem($r);
		}
		$this->output();
	}
	function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."device a WHERE 1 ".$condition;
		echo json_encode($this->db->query_first($sql));
	}
	
	function count_device()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."device a WHERE a.state=1 AND 1 ".$condition;
		echo json_encode($this->db->query_first($sql));
	}
	
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND a.device_token LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//应用id
		if($this->input['app_id'])
		{
			$condition .= ' AND a.appid='.intval($this->input['app_id']);
		}
		//设备标志
		if($this->input['device_token'])
		{
			//$device_token = str_replace("_"," ",$this->input['device_token']);
			$condition .= ' AND a.device_token="'.$this->input['device_token'].'"';
		}
		//类型
		if ($this->input['type'] && intval($this->input['type'])!= -1)
		{
			$types = trim(urldecode($this->input['type']));
			$condition .= ' AND a.types in ('.$types.')' ; 
		}
		//系统
		if ($this->input['system'] && intval($this->input['system'])!= -1)
		{
			$system = trim(urldecode($this->input['system']));
			$condition .= ' AND a.system in ('.$system.')'; 
		}
		//程序
		if ($this->input['client'] && intval($this->input['client']) != -1)
		{
			$client = trim(urldecode($this->input['client']));
			$condition .= ' AND a.program_name in ('.$client .')'; 
		}
		//版本
		if (intval($this->input['debug']) && intval($this->input['debug']) != -1)
		{
			$condition .= ' AND a.debug = '.$this->input['debug'] ; 
		}
		//版本
		if (intval($this->input['client_state']) && intval($this->input['client_state']) != -1)
		{
			$condition .= ' AND a.state = '.$this->input['client_state'] ; 
		}
		//应用
		if(intval($this->input['app']) && intval($this->input['app']) != -1)
		{
			$condition .= ' AND a.appid = '.$this->input['app'];
		}		
		$today = strtotime(date('Y-m-d'));
		//设备创建时间
		if($this->input['device_create_time'])
		{
			$device_create_time = strtotime(trim(urldecode($this->input['device_create_time'])));
			$condition .= " AND a.create_time >= ".$device_create_time." AND a.create_time<=".$today;
		}
		//设备更新时间
		if($this->input['device_update_time'])
		{
			$device_update_time = strtotime(trim(urldecode($this->input['device_update_time'])));
			$condition .= " AND a.update_time >= ".$device_update_time." AND a.update_time<=".$today;
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND a.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND a.create_time <= ".$end_time;
		}
		if($this->input['mobile_client_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['mobile_client_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  a.create_time > ".$yesterday." AND a.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  a.create_time > ".$today." AND a.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  a.create_time > ".$last_threeday." AND a. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND a.create_time > ".$last_sevenday." AND a.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	function instantMessaging()
	{
		if(!$this->input['device_token'])
		{
			$this->errorOutput(NO_DEVICE_TOKEN);
		}
		$devices['device_token'] = $this->input['device_token'];
		$this->addItem($devices);
		$this->output();
	}
	//获取终端类型信息
	public function append_types()
	{
		$type_val = trim(urldecode($this->input['type_val']));
		$arr = array();
		if($type_val)
		{
			$arr['name'] = 'type';
			$arr['show'] = 'type_s';
			$con = " AND device_name LIKE '%" . $type_val . "%'";
		}
		$con .= "  ORDER BY device_name LIMIT 0,5000";
		
		$sql = "SELECT id,device_name,app_id FROM " . DB_PREFIX . "device_library WHERE 1 ".$con;
		$g = $this->db->query($sql);
		$app_id = intval($this->input['app']);
		
		while($j = $this->db->fetch_array($g))
		{
			$app_id_arr = array();
			$app_id_arr = explode(',', $j['app_id']);
			if($app_id && $app_id != -1)
			{
				if(in_array($app_id, $app_id_arr))
				{
					$arr[$j['id']] = $j['device_name'];
				}
			}
			else 
			{
				$arr[$j['id']] = $j['device_name'];
			}
		}
		$this->addItem($arr);
		$this->output();
	}
	//获取终端系统信息
	public function append_system()
	{
		$system_val = trim(urldecode($this->input['system_val']));
		if(!$system_val)
		{
			$system_val = trim(urldecode($this->input['type_val']));
		}
		$arr = array();
		if($system_val)
		{
			$arr['name'] = 'system';
			$arr['show'] = 'system_s';
			$con = " AND device_os LIKE '%" . $system_val . "%'";
		}
		$con .= "  ORDER BY device_os LIMIT 0,5000";
		
		$sql = "SELECT id,device_os,app_id FROM " . DB_PREFIX . "device_os WHERE 1 " . $con;
		$g = $this->db->query($sql);
		$app_id = intval($this->input['app']);
		while($j = $this->db->fetch_array($g))
		{
			$app_id_arr = array();
			$app_id_arr = explode(',', $j['app_id']);
			if($app_id && $app_id != -1)
			{
				if(in_array($app_id, $app_id_arr))
				{
					$arr[$j['id']] = $j['device_os'];
				}
			}
			else 
			{
				$arr[$j['id']] = $j['device_os'];
			}
		}
		$this->addItem($arr);
		$this->output();
	}
	
	//获取client类型信息
	public function append_client()
	{
		$type_val = trim(urldecode($this->input['type_val']));
		if($type_val)
		{
			$arr['name'] = 'client';
			$arr['show'] = 'client_s';
			$con = " AND client_name LIKE '%" . $type_val . "%'";
		}
		$con .= "  ORDER BY client_name LIMIT 0,1000";
		
		$sql = "SELECT id,client_name FROM " . DB_PREFIX . "client WHERE 1 ".$con;
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$arr[$j['id']] = $j['client_name'];
		}
		$this->addItem($arr);
		$this->output();
	}
	
	//获取app信息
	public function append_app()
	{
		$sql = "SELECT appid,appname FROM " . DB_PREFIX . "certificate";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$arr[$j['appid']] = $j['appname'];
		}
		$this->addItem($arr);
		$this->output();
	}
	
	
	/**
	 * 获取设备安装记录
	 * Enter description here ...
	 */
	public function get_device_log()
	{
		$device_token = $this->input['device_token'];
		
		if(!$device_token)
		{
			$this->errorOutput('notoken');
		}
		
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):5;
		$limit = " LIMIT {$offset}, {$count}";
		
		$sql = "SELECT * FROM ".DB_PREFIX."device_log WHERE device_token = '".$device_token."' ORDER BY create_time DESC " . $limit;
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			
			$this->addItem($r);
		}
		$this->output();
	}
}

$out = new client();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>