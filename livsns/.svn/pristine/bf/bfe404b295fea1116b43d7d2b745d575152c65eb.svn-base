<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_collect_update  extends  adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function audit()
	{
		
	}
	
	public function create()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function delete()
	{
		//传过来是集合的id
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "vod_collect WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql_);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		//更新该集合所属类别里的collect_count;
		$sql = "SELECT  vod_sort_id  FROM  ".DB_PREFIX."vod_collect  WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$vod_sort[] = $r;
		}
		
		foreach($vod_sort as $sort)
		{
			$sql = "UPDATE ".DB_PREFIX."vod_media_node SET collect_count = collect_count - 1 WHERE id = ".$sort['vod_sort_id'];
			$this->db->query($sql);
		}
		
	
		//更新该集合里所包含视频的collects
		$sql = "SELECT video_id FROM ".DB_PREFIX."vod_collect_video WHERE collect_id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$video_ids[] = $r;
		}
		
		foreach($video_ids as $k => $id)
		{
			$sql = "SELECT collects FROM ".DB_PREFIX."vodinfo  WHERE  id = ".$id['video_id'];
			$vod_collects = $this->db->query_first($sql);
			
			if($vod_collects)
			{
				$collects = unserialize($vod_collects['collects']);
				unset($collects[intval(urldecode($this->input['id']))]);
				$sql = "UPDATE ".DB_PREFIX."vodinfo SET collects = '".serialize($collects)."' WHERE id = ".$id['video_id'];
				$this->db->query($sql);
			}
			
		}
	
		//vod_collect_video里对应的集合视频
		$sql = "DELETE FROM ".DB_PREFIX."vod_collect_video WHERE collect_id in (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		
		//删除该集合;
		$sql = "DELETE FROM ".DB_PREFIX."vod_collect WHERE id in (".urldecode($this->input['id']).")";
	    $this->db->query($sql);
	    
	    $this->addLogs('delete' , $ret , '' , '' , '');
	    
		$this->addItem("success");
	    $this->output();
		
	}
	
	public function update()
	{
		if(!$this->input['collect_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$up_data = array();
		$fields = ' SET  ';
		
		if($this->input['sort_name'])
		{
			$fields .= '  vod_sort_id = \''.urldecode($this->input['sort_name']).'\',';
		}
		$up_data['vod_sort_id'] = $this->input['sort_name'];
		
		if($this->input['collect_name'])
		{
			$fields .= '  collect_name = \''.urldecode($this->input['collect_name']).'\',';
		}
		$up_data['collect_name'] = $this->input['collect_name'];
		
		if($this->input['comment'])
		{
			$fields .= '  comment = \''.urldecode($this->input['comment']).'\',';
		}
		$up_data['comment'] = $this->input['comment'];
		
		if($this->input['source'])
		{
			$fields .= '  source = \''.urldecode($this->input['source']).'\',';
		}
		$up_data['source'] = $this->input['source'];
		
		$fields .= '  update_time = \''.TIMENOW.'\'';
		$up_data['update_time'] = TIMENOW;
		$up_data['id'] = $this->input['collect_id'];
		
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "vod_collect WHERE id = " . $this->input['collect_id'];
		$pre_data = $this->db->query_first($sql_);
		
	    $sql = "UPDATE ".DB_PREFIX.'vod_collect  ' . $fields .'  WHERE  id = ' . intval(urldecode($this->input['collect_id']));
		$this->db->query($sql);
		
		$this->addLogs('update' , $pre_data , $up_data , '' , '');
		
		/*如果有视频添加就添加进去*/
		if($this->input['ids'])
		{
			$this->add_few_videos();
		}
		
		$ret = array('collect_id' => intval($this->input['collect_id']),'collect_name' => urldecode($this->input['collect_name']));

        $this->addItem($ret);
		$this->output();
		
	}
	
	public function add_few_videos()
	{	
		$collect_id = intval($this->input['collect_id']);
		
		if($this->input['ids'])
		{
			//要添加到的集合的collect_id
			$ids = explode(",",urldecode($this->input['ids']));
			
			//判断这其中有没有视频已经在该集合中了，如果在的话 ，就去除掉
			$sql = "SELECT video_id FROM ".DB_PREFIX."vod_collect_video  WHERE  video_id in (".urldecode($this->input['ids']).") AND collect_id = ".$collect_id;
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
			    
				  $this->db->query($sql);
			   	  $sql = "UPDATE ".DB_PREFIX."vod_collect_video  SET  order_id = '".$this->db->insert_id()."'  WHERE id = '".$this->db->insert_id()."'";
			   	  $this->db->query($sql); 
			}
			
			$count = count($ids);
			
			$sql = "UPDATE ".DB_PREFIX."vod_collect  SET count = count + ".$count."  WHERE id = ".$collect_id;
			$this->db->query($sql);
			
			//获得该集合的集合名称
			$collect_name = urldecode($this->input['collect_name']);
			
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
		
		}
		
	}

}

$out = new vod_collect_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'delete';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>