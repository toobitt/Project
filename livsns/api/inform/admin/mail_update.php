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
define('MOD_UNIQUEID', 'mail'); //模块标识
require ('global.php');
class mailUpdateApi extends adminUpdateBase
{ 
	public function __construct() 
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mail.class.php');
		$this->obj = new mail();
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
	if (!$this->input['token_id']) {
		

		
		foreach($sendto as $to_user_id => $to_user_name)
		{
			$token_data = array(
				'from_user_id' 		=> intval($user_id),
				'from_user_name' 	=> trim($user_name),
				'to_user_id' 		=> intval($to_user_id),
				'to_user_name' 		=> trim($to_user_name),
				'token_key'			=> md5(time().rand(10000).$to_user_name.rand(10)),
				'create_time' 		=> TIMENOW
			);
			$token_id	= $this->obj->create_token($token_data);
			
			
			
			$send_mail_info = array (
				'appid' 			=> intval($this->user['appid']),
				'appname' 			=> trim(($this->user['display_name'])),
				'ip' 				=> hg_getip(),
				'content' 			=> $this->input['content'],
				'create_time' 		=> TIMENOW,
				'update_time' 		=> TIMENOW
			
			);
			
		
			$mail_id 		= $this->obj->create_mail($send_mail_info);
			if (!$mail_id) 
			{
				$this->errorOutput('添加消息失败');
			}
			
			$send_data = array(
				'token_id'			=>$token_id,
				'mail_id'			=> intval($mail_id),
				'from_user_id' 		=> intval($user_id),
				'from_user_name' 	=> trim($user_name),
				'to_user_id' 		=> intval($to_user_id),
				'to_user_name' 		=> trim($to_user_name)
			);
			$send_id = $this->obj->create_send($send_data);
			if (!$send_id) 
			{
				$this->errorOutput('添加消息失败');
			}
		}//end foreach		
	}
	else
	{
		
		foreach($sendto as $to_user_id => $to_user_name)
		{
			$send_mail_info = array (
				'appid' 			=> intval($this->user['appid']),
				'appname' 			=> trim(($this->user['display_name'])),
				'ip' 				=> hg_getip(),
				'content' 			=> $this->input['content'],
				'create_time' 		=> TIMENOW,
				'update_time' 		=> TIMENOW
			
			);
		
			$mail_id 		= $this->obj->create_mail($send_mail_info);	
			if (!$mail_id) 
			{
				$this->errorOutput('添加消息失败');
			}
			
			$send_data = array(
				'token_id'			=> intval($this->input['token_id']),
				'mail_id'			=> intval($mail_id),
				'from_user_id' 		=> intval($user_id),
				'from_user_name' 	=> trim($user_name),
				'to_user_id' 		=> intval($this->input['to_user_id']),
				'to_user_name' 		=> trim($this->input['to_user_name'])
			);
			$send_id = $this->obj->create_send($send_data);
			if (!$send_id) 
			{
				$this->errorOutput('添加消息失败');
			}
		}//end foreach	
	}
		
	$this->addItem($send_id);
	$this->output();
}
	/**
	 * 更新消息内容
	 * @name update
	 * @param $type int 
	 */
	public function update() 
	{
		if (empty ($this->input['id'])) 
		{
			$this->errorOutput("公告ID不能为空");
		}
		if (!$this->input['content']) 
		{
			$this->errorOutput('内容不能为空');
		}
		
		$mail_id = intval($this->input['id']);
		$data = array (
			'content' 		=> ($this->input['content']),
			'update_time'   => TIMENOW
		);
		$cond = " where id=".intval($this->input['id']);
		
		
		if(!$this->obj->update_data('mail',$data,$cond))
		{
			$this->errorOutput('更新不成功');
		}
		$this->addItem($this->input['id']);
		$this->output();
			
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

	}
	
	public function publish()
	{
		
	}

	public function unknow() 
	{
		$this->errorOutput("此方法不存在！");
	}
	public function sort() 
	{
	}
}
$out = new mailUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>