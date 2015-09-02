<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require("./lib/opration.class.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once('./lib/check.class.php');
class oprationApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->opration = new opration();
		$this->check = new check();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		if ($this->input['interview_id'])
		{
			//获取访谈信息时更新用户的在线时间
			$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>intval(urldecode($this->input['interview_id']))));
			//参数接收
			$data = array(
				'interview_id'=>intval(urldecode($this->input['interview_id'])),
				'user_id'=>$this->user['user_id'],
			);
			$data['user_id'] = $data['user_id'] ? $data['user_id'] :0;
			$op = $this->opration->op($data['user_id'],$data['interview_id']);
			$data['op'] = $op;			
		}else {
			$data['op']= array();
		}
		$this->setXmlNode('opration','item');
		$this->addItem($data);
		$this->output();
		
		
	}
	/**
	 * 审核
	 * 
	 */
	public function audit()
	{
		//参数接收
		$data =array(
			'id'=>intval(urldecode($this->input['id'])),
			'state'=>2,
		);
		$interviewId = $this->opration->getId($data['id']);
		if (!$data['id']){
			$this->errorOutput(NOID);
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>$interviewId));
		$this->opration->changeState($data['id'], $data['state']);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 修改
	 * 
	 */
	public function update()
	{
		//参数接收
		$data = array(
			'id'=>intval(urldecode($this->input['id'])),
			'question'=>addslashes(trim(urldecode($this->input['question']))),
		);
		$interviewId = $this->opration->getId($data['id']);
		if (!$data['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>$interviewId));
		$this->opration->updateQuestion($data['id'], $data['question']);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 
	 * 忽略
	 */
	public function ignore()
	{
		//参数接收
		$data = array(
			'id'=>intval(urldecode($this->input['id'])),
			'state'=>3,
		);
		$interviewId = $this->opration->getId($data['id']);
		if (!$data['id'])
		{
			$this->errorOutput(NOID);
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>$interviewId));
		$this->opration->changeState($data['id'], $data['state']);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 引用
	 * 
	 */
	public function quote()
	{
		//参数接收
		$data = array(
			'id'=>intval(urldecode($this->input['id'])),
		);
		$interviewId = $this->opration->getId($data['id']);
		if (!$data['id'])
		{
			$this->errorOutput(NOID);
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>$interviewId));
		
		$data['question'] = $this->opration->getQuestion($data['id']);
		//$data['is_pub'] = $this->opration->getPub($data['id']);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 
	 * 撤销
	 */
	public function undo()
	{
		//参数接收
		$data = array(
			'id'=>intval(urldecode($this->input['id'])),
			'state'=>3,
		);
		$interviewId = $this->opration->getId($data['id']);
		if (!$data['id'])
		{
			$this->errorOutput(NOID);
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>$interviewId));
		
		$this->opration->changeState($data['id'], $data['state']);
		$this->addItem($data);
		$this->output();
	}

	
}
$ouput= new oprationApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>