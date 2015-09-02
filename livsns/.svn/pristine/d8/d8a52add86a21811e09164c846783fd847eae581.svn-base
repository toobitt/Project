<?php
//获取某个交换名片人的信息
define('MOD_UNIQUEID','member_info');
define('SCRIPT_NAME', 'member_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class member_info extends outerReadBase
{
	private $member_mode;
	public function __construct()
	{
		parent::__construct();
		$this->member_mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		//请求的人必须是登陆状态
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//交换人的id
		if(!$this->input['exchange_id'])
		{
			$this->errorOutput(NO_EXCHANGE_ID);
		}
		
		//当前用户有没有激活
		$_self_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_self_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}

		//判断交换的人有没有激活
		$_memberInfo = $this->member_mode->detail(''," AND id = '" .$this->input['exchange_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(HE_HAVE_NOT_ACTIVATED);
		}
		
		//判断我与该用户有没有交换过名片
		if(!$this->member_mode->isHaveExchanged($_memberInfo['id'],$_self_memberInfo['id']))
		{
			$this->errorOutput(YOU_NOT_EXCHANGED_EACHOTHER);
		}
		
		$_memberInfo['avatar'] = $_memberInfo['avatar']?@unserialize($_memberInfo['avatar']):array();
		$this->addItem($_memberInfo);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');