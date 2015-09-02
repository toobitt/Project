<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','pushPlan');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('NUMBER', 5);
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/push_notify.class.php");
define('SCRIPT_NAME', 'PushPlan');
class PushPlan extends cronBase
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
			'name' => '推送消息',	 
			'brief' => '向苹果客户端推送消息',
			'space' => '2',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	/**
	 * 消息推送（支持失败设备重新推送）
	 * Enter description here ...
	 */
	public function show()
	{
		//查询需要推送的全局信息
		$push_sql = "SELECT a.*
					FROM ".DB_PREFIX."advices a 
					WHERE a.is_send=0 
					AND a.send_way=1 
					AND a.state=1 
					AND a.send_time <=".TIMENOW." 
					AND a.is_global=1  
					ORDER BY a.send_time ASC 
					LIMIT 1";
		$message = $this->db->query_first($push_sql);
		
		$fail_id = '';
		//全局推送的消息不存在时，查询发送失败表中记录
		if(!$message['id'] || !$message['message'])
		{
			$start_time 		= strtotime(date('Y-m-d',TIMENOW));
			$end_time 			= $start_time + 86400;
			
			//重复发送次数
			$repeat_push_num 	= defined('REPEAT_PUSH_NUM') ? REPEAT_PUSH_NUM : 0;
			
			//为0时，不重发消息
			if(!$repeat_push_num)
			{
				return false;
			}
			
			//查询当天发送失败的消息,失败次数不超过规定重复发送次数
			$sql = "SELECT * FROM " . DB_PREFIX . "push_failed 
					WHERE fail_num < ".$repeat_push_num." 
					AND create_time > ".$start_time." 
					AND create_time < ".$end_time." 
					ORDER BY update_time ASC LIMIT 1";
			$res = $this->db->query_first($sql);
			if(!$res || !$res['message_id'] || !$res['error_cause'] || !$res['id'])
			{
				return FALSE;
			}
			
			//发送失败的设备信息
			$device_info = unserialize($res['error_cause']);

			if(!$device_info)
			{
				return FALSE;
			}
			
			//查询发送失败消息的内容，模块id
			$sql = "SELECT a.appid,a.message,a.content_id,a.link_module 
					FROM ".DB_PREFIX."advices a 
					WHERE a.id=" . $res['message_id'] . " AND a.state=1 LIMIT 1";
			$message = $this->db->query_first($sql);
		
			if(!$message)
			{
				return FALSE;
			}
			
			//失败消息记录id
			$fail_id = $res['id'];
		}
	
		//推送全局消息，查询消息发送记录队列
		if(!$fail_id)
		{
			$offset_queue 	= $this->db->query_first('SELECT * FROM '.DB_PREFIX.'publish WHERE message_id = '.intval($message['id']));
			$offset 		= $offset_queue['offset'] ? $offset_queue['offset'] : 0;
			
			$limit			= $offset . ',' . NUMBER;
			
			//获取符合消息要求的设备
			$device_info = $this->get_device($message, $limit);
			
			if(!empty($device_info))
			{
				//查询到设备的时候，更新设备记录offset
				$this->db->query('REPLACE INTO '.DB_PREFIX.'publish VALUES('.$message['id'].','.intval($offset+NUMBER).')');
			}
		}
		
		//推送消息
		if(!empty($device_info))
		{
			$info = array(
				'badge' => 1,	
				'sound' => 'default',	
				'text' => $message['message'],	
				'module_id' => $message['link_module']?$message['link_module']:'',	
				'content_id' => $message['content_id'],	
			);
			
			$sql = 'SELECT * FROM ' . DB_PREFIX .'certificate WHERE  appid='.$message['appid'];
			$appcert = $this->db->query_first($sql);
			
			$pushNotify = new pushNotify();
			//记录发送失败的设备
			$failed_push = array();
			foreach ($device_info as $key=>$val)
			{
				$pushNotify->setAPNsHost($key);
				if($key)
				{
					$cert = $appcert['develop'];
				}
				else 
				{
					$cert = $appcert['apply'];
				}
				
				$pushNotify->setCert(ZS_DIR . $cert);
			
				if(!$pushNotify->connectToAPNS())
				{
					foreach ($val as $row)
					{
						$failed_push[$key][]['device_token'] = $row['device_token'];
					}
					//$this->db->query('INSERT INTO '.DB_PREFIX.'push_failed value('.$message['id'].','.$offset.','.NUMBER.', \'LINK_APPLE_SERVER_FAILED\')');
					//$this->errorOutput(LINK_SERVER_FAILED);
				}
				else 
				{
					//推送至客户端
					foreach ($val as $k => $row)
					{
						$is_success = $pushNotify->send($row['device_token'], $info);
						if (!$is_success)
						{
							$failed_push[$key][]['device_token'] = $row['device_token'];
						}
					}
				}
				$pushNotify->closeConnections();
			}
			
			//存在发送失败设备，记录设备信息
			if(!empty($failed_push))
			{
				$failed_push = serialize($failed_push);
				
				//推送全局消息失败
				if(!$fail_id)
				{
					$this->db->query('INSERT INTO '.DB_PREFIX.'push_failed (id,message_id,device_offset,device_num,error_cause,create_time,update_time,fail_num) VALUE(0,' . $message['id'] . ',' . $offset . ',' . NUMBER . ', \'' . $failed_push . '\', ' . TIMENOW . ', ' . TIMENOW . ',0)');
				}
				else //推送发送失败消息再次失败,更新计数和失败设备记录
				{
					$sql = "UPDATE ".DB_PREFIX."push_failed SET fail_num = fail_num + 1, error_cause = '" . $failed_push . "', update_time = '" . TIMENOW . "' WHERE id = " . $fail_id;
					$this->db->query($sql);
				}
			}
			else if($fail_id)//推送失败消息成功，删除失败消息记录
			{
				$sql = "DELETE FROM " . DB_PREFIX . "push_failed WHERE id = " . $fail_id;
				$this->db->query($sql);
			}
		}
		else 
		{
			//满足条件的设备全部发送完，并且不是推送失败消息，更新消息发送状态is_send=1
			$this->db->query('UPDATE '.DB_PREFIX.'advices set is_send=1 WHERE id = '.intval($message['id']));
			$sql = 'DELETE FROM '.DB_PREFIX.'publish WHERE message_id = '.intval($message['id']);
			$this->db->query($sql);
			
			if($fail_id)
			{
				$sql = "DELETE FROM " . DB_PREFIX . "push_failed WHERE id = " . $fail_id;
				$this->db->query($sql);
			}
				
			$this->errorOutput(QUEUE_FINISHED);
		}
	}
	
	/**
	 * 
	 * 获取符合要求的设备信息
	 * @param array $message
	 * @param string $limit
	 */
	private function get_device($message,$limit)
	{
		if(!$message || !$limit)
		{
			return FALSE;
		}
		
		//组装满足消息要求的查询设备sql
		$sql = 'SELECT * FROM '.DB_PREFIX.'device WHERE 1 ';	
		
		//应用id
		if($message['appid'])
		{
			$sql .= ' AND appid='.intval($message['appid']);
		}
		//选择版本
		if($message['debug'] != -1)
		{
			$sql .= ' AND debug='.intval($message['debug']);
		}
		//程序版本
		if($message['client'])
		{
			$client = trim($message['client'],',');
			$sql .= ' AND program_name in ('.$client.')';
		}
		//设备类型
		if($message['device_type'])
		{
			$device_type = trim($message['device_type'],',');
			$sql .= ' AND  types in ('.$device_type.')';
		}
		//设备系统
		if($message['device_os'])
		{
			$device_os = trim($message['device_os'],',');
			$sql .= ' AND  system in ('.$device_os.')';
		}
		
		$today = strtotime(date('Y-m-d'));
		//设备创建时间
		if($message['device_create_time'])
		{
			$sql .= " AND create_time >= ".$message['device_create_time']." AND create_time<=".$today;
		}
		//设备更新时间
		if($message['device_update_time'])
		{
			$sql .= " AND update_time >= ".$message['device_update_time']." AND update_time<=".$today;
		}
		
		//设备状态为正常
		$sql .= ' AND state=1 ORDER BY update_time DESC LIMIT ' . $limit;
		
		//查询符合条件的终端
		$query = $this->db->query($sql);
		
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[$row['debug']][] = $row;
		}
		
		return $arr;
	}
}

include(ROOT_PATH . 'excute.php');
?>