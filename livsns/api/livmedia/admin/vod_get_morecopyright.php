<?php
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
require_once(CUR_CONF_PATH . 'lib/vod_copyright.class.php');
class  vod_get_morecopyright extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:start:开始查找的位置;length:每次查找的个数
	 *功能:获取更多的版本
	 *返回值:所要获得的版本信息
	 * */
	public function more_copyright()
	{
		$offset = intval($this->input['start']);
		$count = intval($this->input['length']);
		$return = array();
		$vodCopyright = new vodCopyright();
		$vodCopyright->set('video_id = '.intval($this->input['id']));
		$vodCopyright->fetch_num($offset,$count);
		$return = $vodCopyright->show();
		$this->addItem($return);
		$this->output();
	}
}

$out = new vod_get_morecopyright();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'more_copyright';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>