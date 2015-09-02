<?php
define('MOD_UNIQUEID','member_message_update');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH.'lib/member_message.class.php');
class memberMessageUpdateApi extends outerUpdateBase
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
	//站内信发送接口
	public function create()
	{
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'user_type'		=> intval($this->input['user_type']),
			'title'			=> trim($this->input['title']),
			'message'		=> $this->input['message'],
			'message_type'	=> $this->input['message_type'],
			'group_id'		=> $this->input['group_id'],
			'group_type' 	=> $this->input['group_type'],
			'post_date'		=> TIMENOW,
			'effective_time'=> intval($this->input['effective_time']),
		);
		if (!trim($data['message']))
		{
			$this->errorOutput('内容不能为空！');
		}
		if (!$data['user_id'])
		{
			$this->errorOutput('未知的用户');
		}
		if ($message_type !=3 && !$group_id && !$receive_ids)
		{
			$this->errorOutput('请填发件人后再发送！');
		}
		$data['group_id'] = $data['group_id'] ? $data['group_id'] : 0;
		$data['message_type'] = $data['message_type'] ? $data['message_type'] : 1;//如果未填写默认为私信
		$data['effective_time'] = $data['effective_time'] ? $data['effective_time'] : 25920000 ; //默认有效期为1个月
		//私信直接插入,接收人用逗号相连
		$receive_ids = $this->input['receive_user_id'];
		$receive_type = $this->input['receive_user_type'];	//目前只支持接收1种用户类型
		//数据纠错
		if ($data['message_type'] != 1)
		{
			$receive_ids = 0;
			$receive_type = 0;
		}
		if ($data['message_type'] !=2)
		{
			$data['group_id'] = 0;
			$data['group_type'] = 0;
		}

		//插入消息内容
		$messageText = $this->msg->insertMessageText($data);
		if (!$messageText['id'])
		{
			//$this->errorOutput('信息插入失败');
			//信息记录失败直接进入草稿箱
			$msg = array(
				'user_id'		=> $data['user_id'],
				'user_type'		=> $data['user_type'],
				'title'			=> $data['title'],
				'message'		=> $data['message'],
				'message_type'	=> $data['message_type'],
				'group_id'		=> $data['group_id'],
				'group_type' 	=> $data['group_type'],
				'post_date'		=> $data['post_date'],
				'effective_time'=> $data['effective_time'],
				'receive_user_id'	=> $receive_ids,
				'receive_user_type'	=> $receive_type,
			);
			$ret = $this->msg->insertDraft($msg);
			if (!$ret['id'])
			{
				$this->errorOutput('信息发送失败，保存草稿失败!');
			}
			else
			{
				$this->errorOutput('信息发送失败，保存至草稿!');
			}
				
		}
		if ($data['message_type'] == 1 && $receive_ids)
		{
			$receiveIds = explode(',', $receive_ids);
			foreach ($receiveIds as $rid)
			{
				//插入消息关系
				$receiveData = array(
					'receive_user_id'	=> $rid,
					'receive_user_type'	=> $receive_type,
					'message_id'	=> $messageText['id'],
					'status'		=> 0,	//默认未读
				);
				$message = $this->msg->insertMessage($receiveData);
				if (!$message['id'])
				{
					//私信插入失败，消息回滚
					$delText = $this->msg->delMessageText($messageText['id']);
					if ($delText)
					{
						//信息记录失败直接进入草稿箱
						$msg = array(
							'user_id'		=> $data['user_id'],
							'user_type'		=> $data['user_type'],
							'title'			=> $data['title'],
							'message'		=> $data['message'],
							'message_type'	=> $data['message_type'],
							'group_id'		=> $data['group_id'],
							'group_type' 	=> $data['group_type'],
							'post_date'		=> $data['post_date'],
							'effective_time'=> $data['effective_time'],
							'receive_user_id'	=> $receive_ids,
							'receive_user_type'	=> $receive_type,
						);
						$ret = $this->msg->insertDraft($msg);
						if (!$ret['id'])
						{
							$this->errorOutput('信息发送失败，保存草稿失败!');
						}
						else
						{
							$this->errorOutput('信息发送失败，保存至草稿!');
						}
					}
					$this->errorOutput('插入消息关系失败');
				}
			}
		}
		$this->addItem($messageText);
		$this->output();
	}
	//保存草稿
	public function saveDraft()
	{
		$data = array(
			'user_id'			=> $this->user['user_id'],
			'user_type'			=> intval($this->input['user_type']),
			'title'				=> trim($this->input['title']),
			'message'			=> $this->input['message'],
			'message_type'		=> $this->input['message_type'],
			'group_id'			=> $this->input['group_id'],
			'group_type'		=> $this->input['group_type'],
			'post_date'			=> TIMENOW,
			'effective_time'	=> intval($this->input['effective_time']),
			'receive_user_id'	=> $rid,
			'receive_user_type'	=> $receive_type,
		);
		//数据纠错
		if ($data['message_type'] != 1)
		{
			$data['receive_user_id'] = 0;
			$data['receive_user_type'] = 0;
		}
		if ($data['message_type'] !=2)
		{
			$data['group_id'] = 0;
			$data['group_type'] = 0;
		}
		//保存草稿
		$ret = $this->msg->insertDraft($data);
		if (!$ret['id'])
		{
			$this->errorOutput('草稿保存失败');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	//私信已读请求接口
	public function personalMessageToRead()
	{
		$ids = trim($this->input['id']);//支持批量，逗号相连
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		if (!$ids || !$user_id)
		{
			return false;
		}
		$data = $this->msg->personalMessageToRead($user_id, $user_type, $ids);
		$this->addItem($data);
		$this->output();
	}
	
	//公共消息已读请求接口
	public function commonMessageToRead()
	{
		$ids = trim($this->input['id']);//支持批量，逗号相连
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		if (!$ids || !$user_id)
		{
			return false;
		}
		$data = $this->msg->commonMessageToRead($user_id, $user_type, $ids);
		$this->addItem($data);
		$this->output();
	}
	//系统消息已读请求接口
	public function globalMessageToRead()
	{
		$ids = trim($this->input['id']);//支持批量，逗号相连
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		if (!$ids || !$user_id)
		{
			return false;
		}
		$data = $this->msg->globalMessageToRead($user_id, $user_type, $ids);
		$this->addItem($data);
		$this->output();
	}
	//消息删除
	public function messageToDelete()
	{
		$ids = trim($this->input['id']);//支持批量，逗号相连
		$user_id = $this->user['user_id'];//用户ID
		$user_type = intval($this->input['user_type']);//用户类型
		if (!$ids || !$user_id)
		{
			return false;
		}
		$data = $this->msg->messageToDelete($user_id, $user_type, $ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
	
	}
	
	public function delete()
	{

	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}
}
$ouput= new memberMessageUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
