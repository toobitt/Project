<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'video_fast_edit');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_get_many_videos extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function select_videos()
	{
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		foreach ($this->input AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$return = $curl->request('vod_get_many_videos.php');
		$this->addItem($return[0]);
		$this->output();
	}
}

$out = new vod_get_many_videos();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'select_videos';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>