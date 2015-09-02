<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
class  vod_video2collect extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id,所要添加到的集合的collect_id
	 *功能:将制定的视频添加到指定的集合中
	 *返回值:成功(success)失败(error)
	 * */
	public function video2collect()
	{
		//要添加的视频的id
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//要添加到的集合的collect_id
		if(!$this->input['collect_id'])
		{
			$collect_id = $this->create_collect(urldecode($this->input['get_contents']));
		}
		else 
		{
			$collect_id = intval($this->input['collect_id']);
		}
		
		$ids = explode(",",urldecode($this->input['id']));
		
		//判断这其中有没有视频已经在该集合中了，如果在的话 ，就去除掉
		$sql = "SELECT video_id FROM ".DB_PREFIX."vod_collect_video  WHERE  video_id in (".urldecode($this->input['id']).") AND collect_id = '".$collect_id."'";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr[] = $r;
		}
		
		if($arr)
		{
			foreach($arr as $v)
			{
				foreach($ids as $k => $ids_v)
				{
					if($v['video_id'] == $ids_v)
					{
						unset($ids[$k]);
					}
				}
			}
		}
		
		
		foreach($ids as $k => $id)
		{
			$sql  = "INSERT INTO ".DB_PREFIX."vod_collect_video  SET  ";
			
			$sql .= " video_id = '".$id."',".
			        " collect_id = '".$collect_id."',".
			        " create_time = '".TIMENOW."',".
			        " update_time = '".TIMENOW."',".
			        " ip = '".hg_getip()."'";
		    
		   if($this->db->query($sql))
		   {
		   	  $sql = "UPDATE ".DB_PREFIX."vod_collect_video  SET  order_id = '".$this->db->insert_id()."'  WHERE id = '".$this->db->insert_id()."'";
		   	  $this->db->query($sql); 
		   }

		}
		
		$count = count($ids);
		
		$sql = "UPDATE ".DB_PREFIX."vod_collect  SET count = count + ".$count."  WHERE id = '".$collect_id."'";
		
		if($this->db->query($sql))
		{
			//添加完之后要更新这几个视频的所属集合collects
			
			
			//获得该集合的集合名称
			$sql = "SELECT collect_name FROM ".DB_PREFIX."vod_collect  WHERE id = '".$collect_id."'";
			$name = $this->db->query_first($sql);
			$collect_name = $name['collect_name'];
			
			//逐个进行更新
			foreach($ids as $k => $id)
			{
				$sql = "SELECT collects FROM ".DB_PREFIX."vodinfo  WHERE  id = ".$id;
				$vod_collects = $this->db->query_first($sql);
				
				if($vod_collects)
				{
					$collects = unserialize($vod_collects['collects']);
				}	
					
				$collects[$collect_id] = $collect_name;
				$sql = "UPDATE ".DB_PREFIX."vodinfo SET collects = '".serialize($collects)."' WHERE id = ".$id;
				$this->db->query($sql);
				
			}
			
			$this->addItem("success");
		}
		else 
		{
			$this->addItem("error");
		}
		
		$this->output();
		
	}
	
	//临时创建一个集合 
	public function create_collect($collect_name)
	{
		$sql = "INSERT INTO ".DB_PREFIX."vod_collect  SET  ";
		$sql.= "collect_name='".$collect_name."',".
		       "admin_name='".urldecode($this->user['user_name'])."',". 
		       "admin_id='".urldecode($this->user['user_id'])."',". 
			   "create_time='".TIMENOW."',".
           	   "update_time='".TIMENOW."'";
		
		$this->db->query($sql);
		$collect_id = $this->db->insert_id();
		/*更新collect_order_id*/
		$sql = 'UPDATE '.DB_PREFIX.'vod_collect SET collect_order_id='.$collect_id.' WHERE id='.$collect_id;
		$this->db->query($sql);
		return $collect_id;
	}
	
}

$out = new vod_video2collect();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'video2collect';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>