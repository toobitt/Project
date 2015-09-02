<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod_get_many_videos.php 9699 2012-08-22 09:16:51Z lijiaying $
***************************************************************************/
require_once('global.php');
class  vod_get_many_videos extends BaseFrm
{
    public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH.'lib/class/curl.class.php';
		$this->curl = new curl($this->settings['livmedia_api']['host'],$this->settings['livmedia_api']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*功能:获取符合条件视频信息
	 *返回值：符合条件视频信息
	 * */
	public function get_many_videos()
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_many_videos');
		$this->curl->addRequestData('start',intval($this->input['start']));
		$this->curl->addRequestData('num',intval($this->input['num']));
		$this->curl->addRequestData('g_switch_mode',intval($this->input['g_switch_mode']));
		
		$return = $this->curl->request('vod_get_many_videos.php');
		$this->addItem($return[0]);
		$this->output();
	}
}

$out = new vod_get_many_videos();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_many_videos';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>