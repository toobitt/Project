<?php

/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :notice_update.php
 * package  :package_name
 * Created  :2013-5-22,Writen by scala
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'notice'); //模块标识
require ('global.php');
class noticeUpdateApi extends adminUpdateBase
{ 
	public function __construct() {
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/notice.class.php');
		$this->obj = new notice();
	}
	public function __destruct() 
	{
		parent :: __destruct();
	}

	/**
	 * 创建公告
	 * @name create
	 * @param $title string 标题
	 * @param $content string 正文内容
	 * @param $user_id int
	 * @param $user_name string
	 * @param $content string 正文内容
	 * @param $appid int 
	 * @param $appname string 
	 * @param $sendto int 发送到的组织结构 
	 * @param $create_time int 创建时间
	 * @param $due_time int 有效时间
	 * @param $update_time int 更新时间
	 * @param $type int 
	 */
	public function create() 
	{
		if (!$this->input['title']) 
		{
			$this->errorOutput("标题不能为空");
		}
		if (!$this->input['content']) 
		{
			$this->errorOutput('内容不能为空');
		}

		$userinfo  			= $this->user;
		$user_id  			= $userinfo['user_id'];
		$user_name 			= $userinfo['user_name'];
		$sendto				= $this->input['sendto'];
		
		if(empty($sendto))
		{
			$this->errorOutput("没有选择组织机构");
		}
		
		
		$info_content = array (
			'title' 			=> $this->input['title'],
			'content' 			=> ($this->input['content']),
			'user_id' 			=> intval($user_id),
			'user_name' 		=> $user_name,
			'appid' 			=> intval($this->user['appid']),
			'appname' 			=> trim(($this->user['display_name'])),
			'ip' 				=> hg_getip(),
			'create_time' 		=> TIMENOW,
			'due_time' 			=> strtotime(($this->input['due_time'])),
			'update_time' 		=> TIMENOW
		);
		
		
		$notice_content_id = $this->obj->create_notice_content($info_content);
		
		
		if (!$notice_content_id)
		{
			$this->errorOutput('添加公告失败');
		}
		
		//send to many orgs
		foreach($sendto as $sendto_org_id=>$sendto_org_name)
		{
			$info = array(
				'notice_id'			=>$notice_content_id,
				'sendto_org_id'		=>$sendto_org_id,
				'sendto_org_name'	=>$sendto_org_name
			);
			
			$notice_id = $this->obj->create_notice($info);
		}
		
			
		$this->addItem($notice_id);
		$this->output();

	}

	/**
	 * 更新公告内容
	 * @name update
	 * @param $title string 标题
	 * @param $content string 正文内容
	 * @param $user_id int
	 * @param $user_name string
	 * @param $content string 正文内容
	 * @param $appid int 
	 * @param $appname string 
	 * @param $sendto int 发送到的组织结构 
	 * @param $create_time int 创建时间
	 * @param $due_time int 有效时间
	 * @param $update_time int 更新时间
	 * @param $type int 
	 */
	public function update() 
	{
		if (empty ($this->input['id'])) 
		{
			$this->errorOutput("公告ID不能为空");
		}
		$this->input['title'] = trim($this->input['title']);
		if (!$this->input['title']) 
		{
			$this->errorOutput("标题不能为空");
		}
		if (!$this->input['content']) 
		{
			$this->errorOutput('内容不能为空');
		}
		
		
		$notice_id = intval($this->input['id']);
		$data = array (
			'title' 		=> $this->input['title'],
			'content' 		=> ($this->input['content']),
			'due_time' 		=> strtotime(($this->input['due_time'])),
			'update_time'   => TIMENOW
		);
		$cond = " where id=".intval($this->input['id']);
		
		//export_var('cond',$this->input['id']);
		
		if(!$this->obj->update_data('notice_content',$data,$cond))
		{
			$this->errorOutput('更新不成功');
		}
		$this->addItem($this->input['id']);
		$this->output();
			

	}

	/**
	 * 审核公告的状态
	 * @name audit
	 * @access public
	 * @param $id int 公告ID
	 * @return $ret array 公告ID,状态
	 */
	public function audit() 
	{
	}

	/**
	 * 根据ID删除公告
	 * @name delete
	 * @param $id int 公告ID
	 * @return $ret int 公告ID
	 */
	public function delete() 
	{
		if(!isset($this->input['id']))
		{
			$this->errorOutput("公告ID不能为空");
		}
		if(!isset($this->input['via']))
		{
			$this->errorOutput("删除方式不对");
		}
		if(isset($this->input['via']))
		{
			$result =  $this->obj->delete_notice($this->input['id'],$this->input['via']);
			if($result==false)
			{
				$this->errorOutput('删除不成功');
			}
			
		}
		
		$this->addItem($result);
		$this->output();
		
		
			
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
$out = new noticeUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>

