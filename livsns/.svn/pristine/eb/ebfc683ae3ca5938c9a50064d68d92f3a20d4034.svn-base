<?php
//验证用户是否已经激活
define('MOD_UNIQUEID','verify_is_activate');
define('SCRIPT_NAME', 'verify_is_activate');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class verify_is_activate extends outerReadBase
{
	private $member_mode;
	private $activate;
	public function __construct()
	{
		parent::__construct();
		$this->member_mode = new member_mode();
		$this->activate = new activate_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	
	public function show()
	{
		//判断有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}

		//判断当前用户有没有激活
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
		
		$_memberInfo['avatar'] = $_memberInfo['avatar']?unserialize($_memberInfo['avatar']):array();
		$this->addItem($_memberInfo);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');