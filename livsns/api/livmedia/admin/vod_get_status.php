<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','vod');
require_once('global.php');
class  vod_get_status extends adminBase
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
	public function get_status()
	{
		echo json_encode($this->settings['video_upload_status']);
	}
}

$out = new vod_get_status();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_status';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>