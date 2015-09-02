<?php
/***************************************************************************
* $Id: member.php 12974 2012-10-25 03:43:11Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class talkUpdateApi extends outerUpdateBase
{
	private $talk;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/talk.class.php';
		$this->talk = new talk();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出会员列表
	 * Enter description here ...
	 */
	public function create()
	{	
		if(empty($this->user['user_id']))
		{
			$this->errorOutput('用户未登录！');
		}
		/*
		if(empty($this->input['content']))
		{
			$this->errorOutput('内容不能为空！');
		}
		*/
		$ret = $this->talk->create($this->user['user_id']);		
	
		$this->setXmlNode('talk','info');
		$this->addItem($ret);
		$this->output();
	}

	public function update()
	{
		$condition = $this->get_condition();

		//$this->addItem($info);
		//$this->output();
	}
	
	public function delete()
	{		
		if($this->user['user_id'])
		{
			if(empty($this->input['tid']))
			{
				$this->errorOutput('未传入内容ID');
			}
			$ret = $this->talk->delete($this->input['tid'],$this->user['user_id']);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('用户未登陆！');
		}
	}

	public function delete_history()
	{
		if($this->user['user_id'])
		{
			if(empty($this->input['tid']))
			{
				$this->errorOutput('未传入内容ID');
			}
			$ret = $this->talk->delete_history($this->input['tid']);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('用户未登陆！');
		}
	}
	
	private function get_condition()
	{
		return $condition;
	}

	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
}

$out = new talkUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>