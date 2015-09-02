<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_upload extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//视频上传
	public function upload()
	{
		if($_FILES['videofile'])
		{
			$this->errorOutput('没有视频文件');
		}
		
		if(!($server = $this->select_server()))
		{
			$this->errorOutput('服务器已经满，不能上传视频，请稍等片刻');
		}
		
		$curl = new curl($server['host'],$server['dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addFile($_FILES);
		$info = $curl->request('create.php');
	}
	
	//去请求接口获取转码服务器的信息,并且选择一个合理的服务器进行上传
	private function select_server()
	{
		$server = $this->settings['videoapi'];
		foreach($server AS $k => $v)
		{
			$curl = new curl($v['host'],$v['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','get_transcode_tasks');
			$info = $this->curl->request('video_transcode.php');
			$server[$k]['server'] = json_decode($info,1);
		}
		
		//分析这些数据
		$space = array('num' => 0,'index' => -1);
		foreach($server AS $k => $v)
		{
			//去除掉请求失败的
			if($v['server']['return'] == 'fail' || intval($v['server']['transcoding_tasks']) >= intval($v['server']['max_transcode_tasks']))
			{
				unset($server[$k]);
				continue;
			}
			
			$server[$k]['space'] = intval($v['server']['transcoding_tasks']) - intval($v['server']['max_transcode_tasks']);
			if($server[$k]['space'] > $space['num'])
			{
				$space['num'] 	= $server[$k]['space'];
				$space['index'] = $k;
			}
		}
		
		if($space['index'] != -1)
		{
			return $server[$space['index']];
		}
		else 
		{
			return false;
		}
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_upload();
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