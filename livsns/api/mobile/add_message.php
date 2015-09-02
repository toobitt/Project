<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','add_message');
require('./global.php');
define('SCRIPT_NAME', 'AddMessage');
class AddMessage extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function count(){}
	function detail(){}
	
	//添加通知
	function show()
	{
		if(!$this->input['send_time'])
		{
			$this->errorOutput('请输入推送时间');
		}
		$message = trim($this->input['message']);
		if(!$message)
		{
			$this->errorOutput('请输入消息内容!');
		}
		
		$org_id 	= $this->user['org_id'];
		$user_id	= $this->user['user_id'];
		$user_name 	= $this->user['user_name'];
		
		if(!intval($this->input['amount']))
		{
			$amount = 1;
		}
		else 
		{
			$amount = intval($this->input['amount']);
		}
		
		$app_id = $device_token = array();
		$app_id = explode(',', $this->input['app_id']);
		
		if(empty($app_id))
		{
			$this->errorOutput('app_id不存在');
		}
		
		$device_token = explode(',', $this->input['device_token']);
		foreach ($app_id as $key => $val)
		{
			$send_way = $res = '';
			//根据appid查询消息发送方式
			$sql = 'SELECT send_way FROM '.DB_PREFIX.'certificate WHERE appid='.$val;
			$res = $this->db->query_first($sql);
			$send_way = $res['send_way'];
			
			//exit;
			$data = array(
				'org_id' 				=> 	$org_id,
				'user_id'				=>	$user_id,
				'message'				=>	$message,
				'username'				=>	$user_name,
				'send_time'				=>	$send_time,
				'create_time'			=>	TIMENOW,
				'is_global'				=>	1,
				'ip'					=>	hg_getip(),
				'link_module' 			=> 	trim($this->input['link_module']),
				'content_id' 			=> 	$this->input['content_id'],
				'amount' 				=> 	$amount,
				'sound' 				=> 	$this->input['sound'],
				'appid' 				=> 	$val,
				'device_token'			=>	$device_token[$key],
				'send_way'				=>	$send_way,
				'client' 				=> 	$this->input['client'],
				'device_type' 			=> 	$this->input['type'],
				'device_os' 			=> 	$this->input['system'],
				'debug' 				=> 	intval($this->input['debug']),
				'device_create_time' 	=> 	$device_create_time,
				'device_update_time' 	=> 	$device_update_time,
				'state'					=> 	1,
				'expiry_time'			=>  intval($this->input['expiry_time']),
			);
			
			$sql = '';
			$sql = 'INSERT INTO '.DB_PREFIX.'advices SET ';
			foreach($data as $k=>$v)
			{
				$sql .= '`'.$k . '`="' . $v . '",';
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
			$inert_id = $this->db->insert_id();
			$this->addItem($inert_id);
		}
		
		
		$this->output();
	}	
}
include(ROOT_PATH . 'excute.php');
?>