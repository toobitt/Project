<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_get_copyright extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id
	 *功能:获取版本
	 *返回值:版本信息
	 **/
	public function  get_copyright()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT contents FROM ".DB_PREFIX."vod_copyright  WHERE id = " . urldecode($this->input['id']);
		$return = $this->db->query_first($sql);
		$return = unserialize($return["contents"]);
		$return['img'] = SOURCE_IMAGE_PATH.'copyright/'.$return['img'];
		/*查询出类别名*/
		$sql = "SELECT sort_name,id FROM ".DB_PREFIX."vod_sort WHERE id= '".$return['vod_sort_id']."'";
		$arr = $this->db->query_first($sql);
		if($arr['sort_name'])
		{
			$return['vod_sort_name'] = $arr['sort_name'];
			$return['vod_sort_id'] = $arr['id'];
		}
		else
		{
			$return['vod_sort_name'] = -1;
			$return['vod_sort_id'] = -1;
		}

		$this->addItem($return);
		$this->output();
	}
}

$out = new vod_get_copyright();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_copyright';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>