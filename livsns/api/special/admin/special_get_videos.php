<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','special');//模块标识
include ROOT_PATH.'lib/class/curl.class.php';
class  special_get_videos extends coreFrm
{
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*功能:获取符合条件视频信息
	 *返回值：符合条件视频信息
	 * */
	public function show()
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_many_videos');
		unset($this->input['a']);
		unset($this->input['mid']);
		foreach($this->input as $k=>$v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$return = $this->curl->request('admin/vod_get_many_videos.php');
		$this->addItem($return[0]);
		$this->output();
	}
}

$out = new special_get_videos();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>