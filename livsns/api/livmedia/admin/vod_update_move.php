<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
class  vod_update_move extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id,sort_name(分类id)
	 *功能:将视频移动到指定的分类中
	 *返回值:移动的视频的id(以逗号隔开的字符串),指定的类别名称
	 * */
	public function  update_move()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT vod_sort_id FROM ".DB_PREFIX."vodinfo  WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{	
			if($r['vod_sort_id'] != urldecode($this->input['sort_name']))
			{
				$old_sort_id[] = $r;//取出此这些视频的原有vod_sort_id
			}
		}
		
		$count = count($old_sort_id);//算出要更新的个数
		
		//更新这些视频的vod_sort_id
		$sql  = "UPDATE ".DB_PREFIX."vodinfo  SET  ";
		$sql .= " vod_sort_id ='".urldecode($this->input['sort_name'])."'";
		$sql .= " WHERE id in (".urldecode($this->input['id']).")";
		
		$this->db->query($sql);
		$sql = "UPDATE  ".DB_PREFIX."vod_media_node  SET  count = count + ".$count."  WHERE id=".urldecode($this->input['sort_name']);
		$this->db->query($sql); 
		
	    //如果原有的vod_sort_id存在
		if($old_sort_id)
		{
			foreach($old_sort_id  as $v)
			{
				$sql = "UPDATE  ".DB_PREFIX."vod_media_node  SET  count = count - 1  WHERE id =".$v['vod_sort_id'];
				$this->db->query($sql); 
			}
		}
	
		$sql = "SELECT name,color FROM ".DB_PREFIX."vod_media_node WHERE id = '".intval($this->input['sort_name'])."'";
		$arr = $this->db->query_first($sql);
		$return['sort_name'] = $arr['name'];
		$return['color'] 	 = $arr['color'];
		$return['id'] = explode(',',urldecode($this->input['id']));
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new vod_update_move();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'update_move';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>