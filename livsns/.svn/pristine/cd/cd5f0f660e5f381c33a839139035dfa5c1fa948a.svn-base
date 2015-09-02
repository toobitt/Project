<?php
//交换名片接口
define('MOD_UNIQUEID','sign_in');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class sign_in extends outerUpdateBase
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
	
	public function create(){}
	public function update(){}
	public function delete(){}
	
	public function run()
	{
		//判断扫描的人有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}

		//判断扫描的人有没有激活身份，并且取出当前扫描人用户信息
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
		
		if($_memberInfo['is_sign'])
		{
			$this->errorOutput(YOU_HAVE_SIGNED);
		}

		//判断大屏标识
		if(!$this->input['screen_id'] || !in_array($this->input['screen_id'],$this->settings['screen_ids']))
		{
			$this->errorOutput(SCREEN_ID_ERROR);
		}
		
		ini_set('precision','14');
		
		//执行签到
		$this->member_mode->update($_memberInfo['id'],array('is_sign' => 1,'screen_id' => $this->input['screen_id'],'sign_time' => microtime(true)));
    	
		//判断当前用户的身份类型，如果是场外嘉宾的话给出错误提示，但是签到还是照常签到
		$code = $this->activate->detail($_memberInfo['activate_code_id']);
		if(!$code || intval($code['guest_type']) == 1)
		{
			$this->errorOutput(NOT_KNOW_IDENTY);			
		}
		
		$this->addItem(array('return' => 1));
    	$this->output();
	}
}

$out = new sign_in();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 