<?php
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  getSettings  extends adminBase
{
    private $curl;
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取水印图片
	public function getWaterPic()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('count',$this->input["water_count"]);
		$this->curl->addRequestData('offset',$this->input["offset"]);
		$ret = $this->curl->request('water_config.php');
		$this->addItem($ret);
		$this->output();
	}
	
	//获取马赛克
	public function getMosaic()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('count',$this->input["mosaic_count"]);
		$this->curl->addRequestData('offset',$this->input["offset"]);
		$ret = $this->curl->request('mosaic.php');
		$this->addItem($ret);
		$this->output();
	}
	
	//获取转码服务器
	public function getTranscodeServer()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCanUseServers');
		$ret = $this->curl->request('transcode_center.php');
		if($ret && $ret[0])
		{
			$ret = $ret[0];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	//获取默认的水印
	public function getDefaultWater()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getDefaultWater');
		$ret = $this->curl->request('vod_config.php');
		if($ret && $ret[0])
		{
			$ret = $ret[0];
		}
		$this->addItem_withkey('water_default', $ret['water_default']);
		$this->output();
	}
	//获取转码配置
	public function getVodConfig()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getVodConfig');
		$ret = $this->curl->request('vod_config_type.php');
		if($ret && $ret[0])
		{
			$ret = $ret[0];
		}
		$this->addItem($ret);
		$this->output();
	}
	public function unknow()
	{
		$this->errorOutput(NOFUC);
	}
}

$out = new getSettings();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
	
?>