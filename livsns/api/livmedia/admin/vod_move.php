<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_move extends adminBase
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
	 *功能:获取指定视频的所在的特定类型下的所有分类
	 *返回值:查找出的指定类型的所有分类的数组
	 * */
	public function move()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node  WHERE  fid = (SELECT vod_leixing FROM ".DB_PREFIX."vodinfo  WHERE id = '".intval($this->input['id'])."')";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		$this->addItem($return);
		$this->output();
	}
}

$out = new vod_move();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'move';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>