<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'livmedia');
require_once('global.php');
class  vod_add_single_video extends adminBase
{
	private $default_type;
    public function __construct()
	{
		parent::__construct();
		$this->default_type = '.wmv,.avi,.dat,.asf,.rm,.rmvb,.ram,.mpg,.mpeg,.3gp,.mov,.mp4,.m4v,.dvix,.dv,.dat,.mkv,.flv,.vob,.ram,.qt,.divx,.cpk,.fli,.flc,.mod,.m4a,.f4v,.3ga,.caf,.mp3,.vob';
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function add_single_video()
	{
		
	}
	
	//重写父类获取config的方法
	public function __getConfig()
	{
		//获取mediaserver的里面视频类型的配置
		if($this->settings['App_mediaserver'])
		{
			$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a','__getConfig');
			$m_config = $curl->request('index.php');
		}
		if($m_config && is_array($m_config))
		{
			$video_type = $m_config[0]['video_type']['allow_type'];
		}
		else 
		{
			$video_type = $this->default_type;
		}
		$video_type_arr = explode(',',$video_type);
		$flash_video_type = '';
		foreach($video_type_arr AS $k => $v)
		{
			$flash_video_type .= '*' . $v . ';'; 
		}
		$video_types = str_replace('.','',$video_type);
		$this->settings['flash_video_type'] = $flash_video_type;
		$this->settings['video_type'] = $video_types;
		parent::__getConfig();
	}
}

$out = new vod_add_single_video();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'add_single_video';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>