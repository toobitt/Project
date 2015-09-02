<?php
define('MOD_UNIQUEID','member_sign');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_sign.class.php';
class member_signUpdateApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->sign = new sign();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 签到功能入口函数 ...
	 */
	public function sign()
	{
		if($this->user['user_id'])
		{
			$member_id=intval($this->user['user_id']);
		}
		$todaysay=$this->input['todaysay']?$this->input['todaysay']:'';//今天想说什么?
		$qdxq=$this->input['qdxq']?$this->input['qdxq']:'';//今日签到心情
		$sign=$this->sign->sign($member_id,$todaysay,$qdxq);
		$status=$sign['status'];
		$msg=array();
		if($status!=1)
		{
			$msg=$sign['msg'];
		}
		switch ($status)
		{
			case 0:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -1:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -2:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -3:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -4:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -5:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -6:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -7:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			case -8:
				if(is_array($msg)&&$msg)
				{
					foreach($msg as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
			default:
				if(is_array($sign)&&$sign)
				{
					foreach($sign as $k => $v)
					$this->addItem_withkey($k, $v);
				}
				break;
		}
		$this->output();
	}
	//空方法
	public function unknow()
	{

		$this->errorOutput(NO_ACTION);
	}


}

$out = new member_signUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>