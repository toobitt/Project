<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID','push_message');
define('SCRIPT_NAME', 'PushMessage');
class PushMessage extends outerReadBase
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
	
	public function show()
	{
		$device_token = $this->input['device_token'];
		if(!$device_token)
		{
			$this->errorOutput(NO_DEVICE_SELCTED);
		}
		
		$app_id = $this->input['app_id'];
		if(!$app_id)
		{
			$this->errorOutput('app_id不存在');
		}
		
		$content = trim($this->input['push_message']);
		if(!$content)
		{
			$this->errorOutput('请输入消息内容!');
		}
		
		//消息基本信息
		$user_id   = intval($this->user['user_id']);
		$user_name = $this->user['user_name'];
		//$org_id  = $this->user['org_id'];
		$ip 	   = hg_getip();
		
		//关联模块标识
		$link_module 	= trim($this->input['link_module']);
		//内容id
		$content_id		= intval($this->input['content_id']);
			
		//为推送准备
		if(!class_exists('pushNotify'))
		{
			include_once CUR_CONF_PATH . 'lib/push_notify.class.php';
		}
		
		$info = array(
				'badge' 		=> 1,	
				'sound' 		=> 'default',	
				'text' 			=> $content,	
				'module_id' 	=> $link_module ? $link_module : '',	
				'content_id' 	=> $content_id,	
		);
		
		$pushNotify = new pushNotify();
			
		
		//记录发送失败的设备
		$failed_push = array();
	   
	    
		//查询设备信息
		$sql = 'SELECT d.appid,d.device_token,d.debug,c.send_way,c.develop,c.apply 
					FROM '.DB_PREFIX.'device d 
				LEFT JOIN '.DB_PREFIX.'certificate c
					ON d.appid = c.appid
				WHERE d.device_token = "'. $device_token .'" AND d.appid = '.$app_id;
		
		$device_info = $this->db->query_first($sql);
			
		if(!$device_info)
		{
			return false;
		}
			
			
		//send_way=1推送方式，等推送完再更新已发送，拉取直接标识已发送，状态state都为已审核
		$is_send = '';
		$is_send = $device_info['send_way'] ? 0 : 1;
		
		$data = array(
			//'org_id'			=> $org_id,
			'user_id'			=> $user_id,
			'message'			=> $content,
			'username'			=> $user_name,
			'send_time'			=> TIMENOW,
			'create_time'		=> TIMENOW,
			'device_token' 		=> $device_info['device_token'],
			'is_global'			=> 0,
			'send_way' 			=> $device_info['send_way'],
			'is_send'			=> $is_send,
			'appid'				=> $device_info['appid'],
			'debug' 			=> $device_info['debug'],
			'state'				=> 1,
			'ip'				=> $ip,
		);
			
		$sql = '';
		$sql = 'INSERT INTO '.DB_PREFIX.'advices SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		$message_id = '';
		$message_id = $this->db->insert_id();
			
		//判断消息发送方式
		if($device_info['send_way'] == 1 && $message_id)//直接推送
		{
			
			$pushNotify->setAPNsHost($device_info['debug']);
			
			$cert = '';
			if($device_info['debug'])
			{
				$cert = $device_info['develop'];
			}
			else 
			{
				$cert = $device_info['apply'];
			}
			
			$pushNotify->setCert(ZS_DIR . $cert);
			
			if(!$pushNotify->connectToAPNS())
			{
				//$this->db->query('INSERT INTO '.DB_PREFIX.'push_failed value('.$message_id.',0,1)');
				$failed_push[$device_info['debug']][]['device_token'] = $device_info['device_token'];
				continue;
			}
			else 
			{
				$is_success = $pushNotify->send($device_info['device_token'], $info);
				if(!$is_success)
				{
					$failed_push[$device_info['debug']][]['device_token'] = $device_info['device_token'];
					//$this->db->query('INSERT INTO '.DB_PREFIX.'push_failed value('.$message_id.',0,1)');
				}
			}
				
			$pushNotify->closeConnections();
			
			$this->db->query('UPDATE '.DB_PREFIX.'advices SET is_send=1 WHERE id = '.$message_id);
		}
		
		//记录发送失败的设备记录
		if(!empty($failed_push))
		{
			$failed_push = serialize($failed_push);
			$sql = 'INSERT INTO '.DB_PREFIX.'push_failed value("",' . $message_id . ',0,1, \'' . $failed_push . '\', ' . TIMENOW . ', ' . TIMENOW . ',0)';
			
			$this->db->query($sql);
		}
		
		$this->addItem($data);
		$this->output();
	}

}

include(ROOT_PATH . 'excute.php');

?>