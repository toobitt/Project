<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  vod_get_many_videos extends adminBase
{
    public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH.'lib/class/curl.class.php';
		$this->curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
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
		if (!$this->curl)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_many_videos');
		$this->curl->addRequestData('k',trim(urldecode($this->input['k'])));
		$this->curl->addRequestData('trans_status',trim(urldecode($this->input['trans_status'])));
		$this->curl->addRequestData('_type',trim(urldecode($this->input['_type'])));
		$this->curl->addRequestData('start',intval($this->input['start']));
		$this->curl->addRequestData('num',intval($this->input['num']));
		$this->curl->addRequestData('g_switch_mode',intval($this->input['g_switch_mode']));
		
		$return = $this->curl->request('admin/vod_get_many_videos.php');
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