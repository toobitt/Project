<?php
//签到大屏互动接口
define('MOD_UNIQUEID','sign_in_interact');
define('SCRIPT_NAME', 'sign_in_interact');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class sign_in_interact extends outerReadBase
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
	
	public function detail(){}
	public function count(){}
	
	public function show()
	{
		//判断有没有传递大屏id号，或者传的大屏id号是否是会场有的id号
		if(!$this->input['screen_id'] || !in_array($this->input['screen_id'],$this->settings['screen_ids']))
		{
			$this->errorOutput(SCREEN_ID_ERROR);
		}
		
		//查询的签到基准时间
		$s_sign_time = $this->input['sign_time'];
		$cond = array(
			'sign_time' => $s_sign_time,
			'screen_id' => $this->input['screen_id'],
		);
		
		//建立长连接
		set_time_limit(0);
		header("Connection: Keep-Alive");
		header("Proxy-Connection: Keep-Alive");
		for ($i = 0, $timeout = 20; $i < $timeout; $i++)
		{
			$data = $this->member_mode->get_signed_members($cond);
			if ($data)
			{
				echo json_encode($data);
				ob_flush();
				flush();
				exit(0);
			}
			sleep(1);
		}
		$this->addItem(array('return' => 'refresh'));
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');