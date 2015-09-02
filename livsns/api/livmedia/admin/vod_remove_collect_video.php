<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
class  vod_remove_collect_video extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id,集合collect_id
	 *功能:将指定视频从某一指定集合中移除
	 *返回值:被移除的视频的id
	 * */
	public function  remove()
	{
		if(!($this->input['id'] && $this->input['collect_id']))
		{
			$this->errorOutput(NOID);
		}
				
		$sql = "DELETE FROM ".DB_PREFIX."vod_collect_video  WHERE video_id in (".urldecode($this->input['id']).")  AND collect_id = ".urldecode($this->input['collect_id']);
		$this->db->query($sql);
		$num = $this->db->affected_rows();
		
		$sql = "UPDATE ".DB_PREFIX."vod_collect SET  count = count - ".$num."  WHERE id = ".urldecode($this->input['collect_id']);
		$this->db->query($sql);
		
		//将属于这个集合的视频中的collects去除掉该集合的记录
		$sql = "SELECT id,collects FROM ".DB_PREFIX."vodinfo  WHERE  id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$vod_collects[$r['id']] = $r['collects'];
		}
		
		if($vod_collects)
		{
		   foreach($vod_collects as $k => $v)
		   {
		   	  $collects = unserialize($v);
		   	  unset($collects[urldecode($this->input['collect_id'])]);
		   	  $sql = "UPDATE ".DB_PREFIX."vodinfo SET collects = '".serialize($collects)."' WHERE id = ".$k;
		   	  $this->db->query($sql);
		   }
				
		}
		
		$this->addItem(array('id'=> urldecode($this->input['id'])));
		$this->output();
	
	}
}

$out = new vod_remove_collect_video();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'remove';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>