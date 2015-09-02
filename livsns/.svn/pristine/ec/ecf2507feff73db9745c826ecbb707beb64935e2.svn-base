<?php
//活跃数统计
define('MOD_UNIQUEID', 'liveness');  //模块标识
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/activate_mode.php');
class livenessStatistics extends outerUpdateBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new activate_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//接收设备信息
	public function create()
	{
		$app_id = $this->input['app_id'];
		$device_token = $this->input['device_token'];
		
		//应用的id
		if(!$app_id)
		{
			$this->errorOutput(NO_APP_ID);
		}
		
		//device_token
		if(!$device_token)
		{
			$this->errorOutput(NO_DEVICE_TOKEN);
		}
		
		//记录活跃
		$ret = $this->mode->addLiveness($app_id,$device_token);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update(){}
    public function delete(){}
}

$out = new livenessStatistics();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>