<?php
//更新device_token
define('MOD_UNIQUEID','update_device');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class update_device extends outerUpdateBase
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
	
	public function create(){}
	public function update(){}
	public function delete(){}
	
	public function run()
	{
		$user_id = $this->user['user_id'];
		if(!$user_id)
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		$device_token = $this->input['device_token'];
		if(!$device_token)
		{
			$this->errorOutput(NO_DEVICE_TOKEN);
		}
		
		$_deviceInfo = array(
			'device_token' 	=> str_replace(' ','',$device_token),
			'user_id' 		=> $user_id,
			'source'       	=> (defined('ISIOS') && ISIOS) ? 1 : ((defined('ISANDROID') && ISANDROID) ? 2 : 2),
		);

		//查询出该用户原来激活时用的设备的device_token
		$this->member_mode->check_device_token($_deviceInfo);
		$this->addItem(array('return' => 1));
		$this->output();
	}
}

$out = new update_device();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 