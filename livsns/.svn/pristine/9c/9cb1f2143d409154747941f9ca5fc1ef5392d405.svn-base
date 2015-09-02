<?php
define('MOD_UNIQUEID','crash_report');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/crash_report_mode.php');
class crash_report_update extends outerUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new crash_report_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	   $app_id = intval($this->input['app_id']);//应用id
	   if(!$app_id)
	   {
	       $this->errorOutput(NO_APP_ID);
	   }
	   
		$data = array(
			'app_id'        => $app_id,
		    'app_name'		=> $this->input['app_name'],//应用名称
		    'systemversion' => $this->input['systemversion'],//设备系统版本
		    'platform'		=> $this->input['platform'],//设备平台
		    'version'		=> $this->input['version'],//应用版本
		    'description'   => $this->input['description'],//描述
		    'log'			=> $this->input['log'],//日志
		    'type'			=> $this->input['type'],//设备类型
		    'debug'			=> intval($this->input['debug']),//是否调试模式
		    'ddversion'		=> $this->input['ddversion'],//叮当版本
		    'isdev'			=> intval($this->input['isdev']),//是否为开发版本
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update(){}
	public function delete(){}
	protected function verifyToken(){}
}

$out = new crash_report_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 