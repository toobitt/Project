<?php
define('MOD_UNIQUEID','member_message');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH.'lib/member_message.class.php');
class memberMessageApi extends outerReadBase
{

	public function __construct()
	{
		parent::__construct();
		$this->msg = new memberMessage();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{	
	}
	public function detail()
	{
	}

	public function count()
	{
	}
	
	//个人私信未读
	public function personalMessageForUnread()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		$data = $this->msg->personalMessageForUnread($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//个人私信已读
	public function personalMessageForRead()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		$data = $this->msg->personalMessageForRead($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//个人私信已删除
	public function personalMessageForDelete()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		$data = $this->msg->personalMessageForDelete($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//个人已发送信息
	public function personalMessageFormSelf()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		$data = $this->msg->personalMessageFormSelf($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	//公共消息未读
	public function commonMessageForUnread()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = $this->input['user_type'];//用户类型
		$group = $this->input['group_id'];//用户所在组
		$group_type = $this->input['group_type'];//用户组类型
		//$group_type = $user_type //此处校正，防止信息串号
		$data = $this->msg->commonMessageForUnread($user_id, $user_type, $group, $group_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//公共消息已读
	public function commonMessageForRead()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = $this->input['user_type'];//用户类型
		$group = $this->input['group_id'];//用户所在组
		$group_type = $this->input['group_type'];//用户组类型
		//$group_type = $user_type //此处校正，防止信息串号
		$data = $this->msg->commonMessageForRead($user_id, $user_type, $group, $group_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//系统消息未读
	public function globalMessageForUnread()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = $this->input['user_type'];//用户类型
		$data = $this->msg->globalMessageForUnread($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//系统消息已读
	public function globalMessageForRead()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = $this->input['user_type'];//用户类型
		$data = $this->msg->globalMessageForRead($user_id, $user_type);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	//草稿箱
	public function personMessageForNotSend()
	{
		$user_id = $this->user['user_id'];//用户ID
		$user_type = $this->input['user_type'];//用户类型
	}
	
	private function get_condition()
	{
		$condition = " AND status = 1 "; 		
		return $condition;
	}
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}
	
	public function test()
	{
		$this->msg->getGroupFromMembers();
	}
}

$ouput = new memberMessageApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>