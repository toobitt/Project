<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  video_sort_type extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取配置里面的值
	public function get_sort_type()
	{
		echo json_encode($this->settings['video_upload_type']);
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
		
}

$out = new video_sort_type();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_sort_type';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>