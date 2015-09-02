<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','client');
define('SCRIPT_NAME', 'mobileDeviceUpdate');
class mobileDeviceUpdate extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function count(){}
	function detail(){}
	
	
	//设备注册更新信息，设备记录存在时不需要对类型再判断
	public function show()
	{
		$device_token = $this->input['device_token'];
		if(!$device_token)
		{
			hg_mobile_client_stat($this->input);
			$this->errorOutput(NO_DEVICE_SELCTED);
		}
		
		$appid = intval($this->user['appid']);
		if(!$appid)
		{
			$this->errorOutput(NO_APPID);
		}
		
		$user_name = $this->user['user_name'];
		$user_id = intval($this->input['member_id']);
		
		//设备类型
		$device_name = trim($this->input['types']);
		//设备系统
		$device_os = trim($this->input['system']);
		//客户端信息
		$program_name = trim($this->input['program_name']);
		/*if(!$program_name)
		{
			$this->errorOutput(NO_PROGRAM_NAME);
		}*/
		$client_name = $program_name . trim($this->input['program_version']);
		
		if ($this->input['program_limit'] && $program_name)
		{
			$program_arr = explode(',', $this->input['program_limit']);
			if($program_arr)
			{
				if(!in_array($program_name, $program_arr))
				{
					$this->errorOutput(PROGRAM_NAME_ERROR);
				}
			}
		}
		
		$umeng_channel 	= trim($this->input['umeng_channel']);
		$phone_num		= trim($this->input['phone_num']);
		$long			= trim($this->input['long']);
		$lati			= trim($this->input['lati']);
		$iccid			= trim($this->input['iccid']);
		$imei			= trim($this->input['imei']);
		$agent			= addslashes($_SERVER['HTTP_USER_AGENT']);
		$ip 			= hg_getip();
		$debug			= intval($this->input['debug']);
		$uuid			= trim($this->input['uuid']);
		
		$data = array(
			'user'				=> $user_name,
			'debug' 			=> $debug,
			'long' 				=> $long,
			'lati' 				=> $lati,
			'update_time' 		=> TIMENOW,
			'state'				=> 1,
			'appid' 			=> $appid,
			'device_token' 		=> $device_token,
			'umeng_channel' 	=> $umeng_channel,
			'phone_num' 		=> $phone_num,
			'iccid' 			=> $iccid,
			'imei'				=> $imei,
			'ip'				=> $ip,
			'agent'				=> $agent,
			'uuid'				=> $uuid,
			'platform_id'		=> trim($this->input['platform_id']),
		);
		
		if($user_id)
		{
			$data['user_id'] = $user_id;
		}
		if($this->input['referrer'])
		{
			$data['referrer'] = $this->input['referrer'];
		}
		
		
		$res = '';
		$imei_tag = false;
		if($imei)
		{
			//根据imei查询设备是否存在
			$sql = 'SELECT * FROM ' . DB_PREFIX .'device WHERE imei ="' . $imei .'" AND appid = '.$appid;
			$res = $this->db->query_first($sql);
			if($res)
			{
				$imei_tag = true;
			}
		}
		
		//如果不以device_token更新imei,需改为if(!$res && !$imei)
		if(!$res)
		{
			//根据设备标识查询设备是否存在
			$sql = 'SELECT * FROM ' . DB_PREFIX .'device WHERE device_token ="' . $device_token .'" AND appid='.$appid;
			$res = $this->db->query_first($sql);
		}
		
		//设备记录存在
		$update_flag = false;
		if($res)
		{
			$update_flag = true;
			if($device_os)
			{
				/**********************设备系统************************************/
				//查询传入的设备系统在device_os是否存在
				$sql = 'SELECT id,app_id FROM '.DB_PREFIX.'device_os WHERE device_os="'.$device_os.'"';
				$r = $this->db->query_first($sql);
				if($r)
				{
					//设备系统存在并且和更新前不同，更新设备系统计数
					if($res['system'] != $r['id'])
					{
						if($res['system'])
						{
							//原系统计数－1
							$sql = 'UPDATE '.DB_PREFIX.'device_os SET amount = amount-1 WHERE id='.$res['system'];
							$this->db->query($sql);
						}
						
						//去掉表中重复app_id
						$app_ids = make_appid_unique($r['app_id'], $appid);
						//新设备系统 +1
						$sql = 'UPDATE '.DB_PREFIX.'device_os SET app_id="'.$app_ids.'",amount=amount+1 WHERE id='.$r['id'];
						$this->db->query($sql);
						$data['system'] = $r['id'];
					}
				}
				else //设备系统不存在
				{
					if($res['system'])
					{
						//原系统计数－1
						$sql = 'UPDATE '.DB_PREFIX.'device_os SET amount = amount-1 WHERE id='.$res['system'];
						$this->db->query($sql);
					}
					
					//新设备系统插入
					$sql = 'INSERT INTO '.DB_PREFIX.'device_os SET amount=amount+1,app_id=' . $appid . ',device_os="'.$device_os.'",create_time='.TIMENOW;
					$this->db->query($sql);
					$data['system'] = $this->db->insert_id();
				}
			}
			//类型字段设置过小，导致类型数据混乱，添加类型更新
			/**********************设备类型************************************/
			if($device_name)
			{
				//查询传入类型在device_library是否存在
				$sql = 'SELECT id,app_id FROM '.DB_PREFIX.'device_library WHERE device_name="'.$device_name.'"';
				$r = $this->db->query_first($sql);
				if($r)
				{
					//类型存在并且和更新前不同
					if($res['types'] != $r['id'])
					{
						if($res['types'])
						{
							//原类型－1
							$sql = 'UPDATE '.DB_PREFIX.'device_library SET amount = amount-1 WHERE id='.$res['types'];
							$this->db->query($sql);
						}
						
						//去掉表中重复app_id
						$app_ids = make_appid_unique($r['app_id'], $appid);
						//新类型+1
						$sql = 'UPDATE '.DB_PREFIX.'device_library SET app_id="'.$app_ids.'",amount=amount+1 WHERE id='.$r['id'];
						$this->db->query($sql);
						$data['types'] = $r['id'];
					}
				}
				else //类型不存在
				{
					if($res['types'])
					{
						//原类型计数－1
						$sql = 'UPDATE '.DB_PREFIX.'device_library SET amount = amount-1 WHERE id='.$res['types'];
						$this->db->query($sql);
					}
					//新类型插入
					$sql = 'INSERT INTO '.DB_PREFIX.'device_library SET amount=amount+1,app_id='. $appid .',device_name="'.$device_name.'",create_time='.TIMENOW;
					$this->db->query($sql);
					$data['types'] = $this->db->insert_id();
				}
			}
			/*******************************客户端************************************/
			if($client_name)
			{
				$sql = 'SELECT id FROM '.DB_PREFIX.'client WHERE client_name="'.$client_name.'"';
				$r = $this->db->query_first($sql);
				if($r)
				{
					//客户端存在并且和原客户端不同
					if($res['program_name'] != $r['id'])//客户端更新
					{
						if($res['program_name'])
						{
							//原客户端计数－1
							$sql = 'UPDATE '.DB_PREFIX.'client SET amount = amount-1 WHERE id='.$res['program_name'];
							$this->db->query($sql);
						}
						
						//客户端存在＋1
						$sql = 'UPDATE '.DB_PREFIX.'client SET amount=amount+1 WHERE id='.$r['id'];
						$this->db->query($sql);
						$data['program_name'] = $r['id'];
					}
				}
				else 
				{
					//原客户端计数－1
					if($res['program_name'])
					{
						$sql = 'UPDATE '.DB_PREFIX.'client SET amount = amount-1 WHERE id='.$res['program_name'];
						$this->db->query($sql);
					}
					
					$sql = 'INSERT INTO '.DB_PREFIX.'client SET amount=amount+1,client_name="'.$client_name.'",create_time='.TIMENOW;
					$this->db->query($sql);
					$data['program_name'] = $this->db->insert_id();
				}
			}
			
			//如果是安装，安装计数+1
			if($this->input['insta'])
			{
				$data['insta_num'] = $res['insta_num'] + 1;
				
				$data_log = array(
					'appid' 		=> $appid,
					'device_token'	=> $device_token,
					'program_name'	=> $client_name,
					'device_os'		=> $device_os,
					'create_time'	=> TIMENOW,
					'ip'			=> $ip,
					'device_name'	=> $device_name,
					'umeng_channel'	=> $umeng_channel,
					'longitude'		=> $long,
					'latitude'		=> $lati,
					'phone_num'		=> $phone_num,
					'imei'			=> $imei,
					'iccid' 		=> $iccid,
					'agent'			=> $agent,
				);
				//记录设备安装程序记录
				//$sql = "INSERT INTO ".DB_PREFIX."device_log SET appid = '".$appid."', device_token = '".$device_token."',program_name='".$client_name."',device_os = '".$device_os."',create_time = '".TIMENOW."',ip = '".hg_getip()."'";
				$sql = "INSERT INTO ".DB_PREFIX."device_log SET ";
				foreach($data_log as $k=>$v)
				{
					$sql .= "`".$k . "`='" . $v . "',";
				}
				$sql = rtrim($sql,',');
				
				$this->db->query($sql);
			}
		
			//更新设备信息
			$sql = "UPDATE ".DB_PREFIX."device SET ";
			foreach($data as $k=>$v)
			{
				$sql .= "`".$k . "`='" . $v . "',";
			}
			$sql = rtrim($sql,',');
			if($imei_tag)
			{
				$sql .= " WHERE imei = '" . $imei . "'";
			}
			else 
			{
				$sql .= " WHERE device_token='".$data['device_token']."'";
			}
			
			$sql .= " AND appid = " . $appid;
		}
		else //设备记录不存在
		{
			/******************************查找设备库***************************************/
			if($device_name)
			{
				$sql = 'SELECT id,app_id FROM '.DB_PREFIX.'device_library WHERE device_name="'.$device_name.'"';
				$r = $this->db->query_first($sql);
				//不存在插入
				if (!$r)
				{
					if($device_name)
					{
						$sql = 'INSERT INTO '.DB_PREFIX.'device_library SET amount=amount+1,app_id='.$appid.',device_name="'.$device_name.'",create_time='.TIMENOW;
						$this->db->query($sql);
						$data['types'] = $this->db->insert_id();	
					}
				}
				else//存在设备计数＋1
				{
					//去掉表中重复app_id
					$app_ids = make_appid_unique($r['app_id'], $appid);
					//设备库计数＋1
					$sql = 'UPDATE '.DB_PREFIX.'device_library SET app_id="'.$app_ids.'",amount = amount+1 WHERE id='.$r['id'];
					$this->db->query($sql);
					$data['types'] = $r['id'];
				}
			}
			/******************************查找设备系统***************************************/
			if($device_os)
			{
				$sql = 'SELECT id,app_id FROM '.DB_PREFIX.'device_os WHERE device_os="'.$device_os.'"';
				$r = $this->db->query_first($sql);
				if (!$r)
				{
					if($device_os)
					{
						$sql = 'INSERT INTO '.DB_PREFIX.'device_os SET amount=amount+1,app_id='.$appid.',device_os="'.$device_os.'",create_time='.TIMENOW;
						$this->db->query($sql);
						$data['system'] = $this->db->insert_id();
					}
				}
				else
				{
					//去掉表中重复app_id
					$app_ids = make_appid_unique($r['app_id'], $appid);
					
					//设备系统计数＋1
					$sql = 'UPDATE '.DB_PREFIX.'device_os SET app_id="'.$app_ids.'",amount = amount+1 WHERE id='.$r['id'];
					$this->db->query($sql);
					$data['system'] = $r['id'];
				}
			}
			
			/******************************查找客户端***************************************/
			if($client_name)
			{
				$sql = 'SELECT id FROM '.DB_PREFIX.'client WHERE client_name="'.$client_name.'"';
				$r = $this->db->query_first($sql);
				if (!$r)
				{
					if($client_name)
					{
						$sql = 'INSERT INTO '.DB_PREFIX.'client SET amount=amount+1,client_name="'.$client_name.'",create_time='.TIMENOW;
						$this->db->query($sql);
						$data['program_name'] = $this->db->insert_id();
					}
				}
				else
				{
					//系统存在计数＋1
					$sql = 'UPDATE '.DB_PREFIX.'client SET amount=amount+1 WHERE id='.$r['id'];
					$this->db->query($sql);
					$data['program_name'] = $r['id'];
				}
			}
			/******************************查找客户端结束***************************************/
			
			//插入证书表
			if($appid)
			{
				$sql = 'SELECT appid FROM '.DB_PREFIX.'certificate WHERE appid='.$appid;
				$v = $this->db->query_first($sql);
				if(!$v['appid'])
				{
					$app_sql = "INSERT INTO ".DB_PREFIX."certificate SET appid=".$appid.",appname='".$this->user['display_name']."'";
					$this->db->query($app_sql);
				}
			}
			
			//如果是安装,安装计数为1
			if($this->input['insta'])
			{
				$data['insta_num'] = 1;
				
				$data_log = array(
					'appid' 		=> $appid,
					'device_token'	=> $device_token,
					'program_name'	=> $client_name,
					'device_os'		=> $device_os,
					'create_time'	=> TIMENOW,
					'update_time'	=> TIMENOW,
					'ip'			=> $ip,
					'device_name'	=> $device_name,
					'umeng_channel'	=> $umeng_channel,
					'longitude'		=> $long,
					'latitude'		=> $lati,
					'phone_num'		=> $phone_num,
					'imei'			=> $imei,
					'iccid' 		=> $iccid,
					'agent'			=> $agent,
				);
				//记录设备安装程序记录
				$sql = "INSERT INTO ".DB_PREFIX."device_log SET ";
				foreach($data_log as $k=>$v)
				{
					$sql .= "`".$k . "`='" . $v . "',";
				}
				$sql = rtrim($sql,',');
				$this->db->query($sql);
			}
			
			//插入设备信息
			$data['create_time'] = TIMENOW;
			$sql = "INSERT INTO ".DB_PREFIX."device SET ";
			foreach($data as $k=>$v)
			{
				$sql .= "`".$k . "`='" . $v . "',";
			}
			$sql = rtrim($sql,',');
		}
		$this->db->query($sql);
		
		//如果是更新进来，判断appid是否设置了关联appid,设置了查询关联appid中是否存在设备，存在状态更新为3
		if($update_flag)
		{
			$sql = "SELECT link_appid FROM ".DB_PREFIX."certificate WHERE appid = ".$appid;
			$res = $this->db->query_first($sql);
			$link_appid = $res['link_appid'];
			if($link_appid && $link_appid != -1 && $link_appid != $appid)
			{
				$sql = "SELECT device_token FROM ".DB_PREFIX."device WHERE appid = ".$link_appid." AND device_token = '".$device_token."' AND state = 1";
				$q = $this->db->query_first($sql);
				$link_device_token = $q['device_token'];
				if($link_device_token)
				{
					$sql = "UPDATE ".DB_PREFIX."device SET state = 3 WHERE appid = ".$link_appid." AND device_token = '".$link_device_token."'";
					$this->db->query($sql);
				}
			}
		}
		hg_mobile_client_stat($this->input);
		$this->addItem('success');
		$this->output();
	}

}

include(ROOT_PATH . 'excute.php');

?>