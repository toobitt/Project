<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :message_update.php
 * package  :package_name
 * Created  :2013-5-23,Writen by scala
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'message'); //模块标识
require ('global.php');
class messageUpdateApi extends adminUpdateBase
{ 
	public function __construct() 
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/message.class.php');
		$this->obj = new message();
	}
	public function __destruct() 
	{
		parent :: __destruct();
	}

	/**
	 * 创建消息
	 * @name create
	 */
public function create() {

	if (!$this->input['content']) 
	{
		$this->errorOutput('内容不能为空');
	}
	
	//发送者的信息
	$userinfo 					= $this->user;
	$user_id 					= $userinfo['user_id'];
	$user_name 					= $userinfo['user_name'];


	//接受者信息
	$sendto 				= $this->input['sendto'];
	
	if(empty($sendto))
	{
		$this->errorOutput("息未指定发送给谁");
	}
	
	/*
	 * 如果是首次建立会话,则新建会话，并添加第一条新信息
	 */
	if (!$this->input['session_id']) {
		//the sent message's info 
		$send_message_info = array (
			'user_id' 			=> intval($user_id),
			'user_name' 		=> trim($user_name),
			'appid' 			=> intval($this->user['appid']),
			'appname' 			=> trim(($this->user['display_name'])),
			'ip' 				=> hg_getip(),
			'content' 			=> $this->input['content'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW
			
		);
		
		$message_id = $this->obj->create_message($send_message_info);
		
		if (!$message_id) 
		{
			$this->errorOutput('添加消息失败');
		}
		
		
		
		foreach($sendto as $to_user_id => $to_user_name)
		{
			$session_data = array(
				'message_id'		=> intval($message_id),
				'from_user_id' 		=> intval($user_id),
				'from_user_name' 	=> trim($user_name),
				'to_user_id' 		=> intval($to_user_id),
				'to_user_name' 		=> trim($to_user_name),
				'create_time' 		=> TIMENOW
			);
			$session_id = $this->obj->create_session($session_data);
		
			
		}
		if (!$session_id) 
		{
			$this->errorOutput('添加消息失败');
		}


	} 
	else 
	{
		$info = array (
			'appid' => intval($this->user['appid']),
			'appname' => trim(($this->user['display_name'])),
			'ip' => hg_getip(),
			'content' => $this->input['content'],
			'update_time' => TIMENOW,
			
		);
		$message_id = $this->obj->create_message($info);
		if (!$message_id)
		{
			$this->errorOutput('添加消息失败');
		}
			
	}
	
	$this->addItem($this->input['id']);
	$this->output();
}
	/**
	 * 更新消息内容
	 * @name update
	 * @param $type int 
	 */
	public function update() 
	{
		if (empty ($this->input['id'])) {
			$this->errorOutput("消息ID不能为空");
		}
		if (!$this->input['content']) {
			$this->errorOutput('内容不能为空');
		}
		$message_id = intval($this->input['id']);
		$data = array (
			'content' => ($this->input['content']),
		);
		$cond = " where id=".intval($this->input['id']);
		
		if(!$this->obj->update_message($data,$cond))
			$this->errorOutput('更新不成功');

	}

	/**
	 * 审核消息的状态
	 * @name audit
	 */
	public function audit() 
	{
	}

	/**
	 * 根据ID删除消息
	 * @name delete
	 */
	public function delete() {
		if (empty ($this->input['id'])) 
		{
			$this->errorOutput("会话的ID不能为空");
		}
		/*
		$sql = "DELETE FROM " . DB_PREFIX . "message WHERE session_id=".intval($this->input['id']);
		if(!$this->db->query($sql))
		{
			$this->errorOutput('删除不成功');
		}
			
		*/
		$sql = "DELETE FROM " . DB_PREFIX . "session WHERE id=".intval($this->input['id']);
		if(!$this->db->query($sql))
		{
			$this->errorOutput('删除不成功');
			
		}
		
		$this->addItem($this->input['id']);
		$this->output();
			
	}
	
	public function publish()
	{
		
	}
	
	public function update_read()
	{
		if (empty ($this->input['id'])) 
		{
			$this->errorOutput("没有选择用户");
		}
		$sql = "update ".DB_PREFIX."session set state=1 where to_user_id=".$this->input['id'];
		if(!$this->db->query($sql))
		{
			$this->errorOutput('删除不成功');
			
		}
		
		$this->addItem($this->input['id']);
		$this->output();
		
	}

	public function unknow() 
	{
		$this->errorOutput("此方法不存在！");
	}
	public function sort() 
	{
	}
}
$out = new messageUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>