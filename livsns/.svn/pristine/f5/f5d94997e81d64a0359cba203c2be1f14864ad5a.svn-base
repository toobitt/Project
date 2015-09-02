<?php
define('SCRIPT_NAME', 'PushNoticeUpdate');
define('MOD_UNIQUEID','push_notice');
require_once('./global.php');
require_once('../lib/jpush.class.php');
class PushNoticeUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function sort()
	{
	}
	function audit()
	{
	}
	function publish()
	{
	}
	private function push_notice($type)
	{
		$app_val = intval($this->input['app_val']);
		if(!$app_val)
		{
			$this->errorOutput('没有app信息');
		}
		$content = $this->input['notice'];
		if(!$content)
		{
			$this->errorOutput('请输入发送内容');
		}
		if(!$this->input['platform'])
		{
			$this->errorOutput('请选择客户端平台');
		}
		if($this->input['receiver_type'] !=4 && !$this->input['receiver_value'])
		{
			$this->errorOutput('请输入要求的标识');
		}
		else if($this->input['receiver_type'] == 4)
		{
			$this->input['receiver_value'] = '';
		}
		
		$title = $this->input['title'];
		
		$sql = "SELECT app_key,master_secret FROM ".DB_PREFIX."app_info WHERE id=".$app_val;
		$app_info = $this->db->query_first($sql);
		//$app_info = explode('@', $app_val);	
		$appkey = $app_info['app_key'];
		$masterSecret = $app_info['master_secret'];
		
		$receiver_type = $this->input['receiver_type'];		
		$receiver_value = $this->input['receiver_value'];	
		
		$platform = implode(',', $this->input['platform']);
		
		if($type == 'update') 
		{
			$id = intval($this->input['id']);
			if(!$id)
			{
				$this->errorOutput('没有id');
			}
			$sendno = $this->input['sendno'];//覆盖上一条
		}
		else 
		{
			$sql = "SELECT max(id) from ".DB_PREFIX."push_notice";
			$res = $this->db->query_first($sql);
			$sendno = $res['max(id)']+1;
		}
		$msg_content = json_encode(array('n_builder_id'=>0, 'n_title'=>$title, 'n_content'=>$content));        
		$obj = new jpush($masterSecret,$appkey);	
		$res_arr = $obj->send($sendno, $receiver_type, $receiver_value, 1, $msg_content, $platform);	
		
		$data = array(
			'app_id' => $app_val,
			'create_time'=>TIMENOW,
            'update_time'=>TIMENOW,
            'user_name'=>trim($this->input['user_name']),
			'sendno' => $sendno,
			'title' => $title,
			'content' => $content,
			'errcode' => $res_arr['errcode'],
			'errmsg' => $res_arr['errmsg'],
			'receiver_type' => $receiver_type,
			'receiver_value' => $receiver_value,
			'platform' => $platform,
			'total_user' => '',
			'send_cnt' => '',
		);
		if($type == 'create')
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'push_notice SET ';
			foreach($data as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$this->db->query(rtrim($sql, ','));
		
			$data['id'] = $this->db->insert_id();
		}
		else if($type == 'update')
		{
			$sql = 'UPDATE '.DB_PREFIX.'push_notice SET ';
			foreach($data as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql,',');
			$sql .= ' WHERE id='.$id;
			$this->db->query($sql);
			$data['id'] = $id;
		}
	
		$this->addItem($data);
		$this->output();
	}
	//发送通知，不覆盖
	function create()
	{
		$this->push_notice('create');
	}
	//重新发送，覆盖上一条
	function update()
	{
		$this->push_notice('update');
	}
	function  delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'push_notice WHERE id in('.$ids.')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');