<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
define('MOD_UNIQUEID', 'activate');  //模块标识
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/activate_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_mode.php');
include_once ROOT_PATH . 'lib/class/applant.class.php';

class activateStatistics extends outerUpdateBase
{
	private $mode;
	private $applant;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new activate_mode();
		$this->applant = new applant();
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
		
		//激活
		if(!$this->mode->isHasStatistic($app_id,$device_token))
		{
			$data = array(
				'device_token' 	=> $device_token,
				'app_id' 		=> $app_id,
				'source' 		=> $this->input['source'],
				'debug' 		=> $this->input['debug']?1:0,
				'gps_long' 		=> $this->input['gps_long'],
				'gps_lati' 		=> $this->input['gps_lati'],
				'iccid' 		=> $this->input['iccid'],
				'imei' 			=> $this->input['imei'],
				'phone_num'		=> $this->input['phone_num'],
				'update_time' 	=> TIMENOW,
				'create_time' 	=> TIMENOW,
				'ip'			=> hg_getip(),
				'activate_num'	=> 1,
			);
			$this->mode->create($data);
			//如果是ios客户端同时更新app_info表中 对应的IOS测试安装数量或者发布安装数量+1
			$source = intval($this->input['source']);
			$debug = intval($data['debug']);
			if($source == 2)
			{
				$this->applant->updateIosCounts($app_id,$debug);
			}
		}

		//判断今天有没有活跃
		if($this->mode->isTodayHasLiveness($app_id,$device_token))
		{
			$this->errorOutput(TODAY_HAS_LIVENESS);
		}
		else 
		{
		    //记录活跃
    		$ret = $this->mode->addLiveness($app_id,$device_token,$this->input['source']);
    		if($ret)
    		{
    			$this->addItem('success');
    			$this->output();
    		}
		}
	}
	
	public function update(){}
    public function delete(){}
}

$out = new activateStatistics();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>