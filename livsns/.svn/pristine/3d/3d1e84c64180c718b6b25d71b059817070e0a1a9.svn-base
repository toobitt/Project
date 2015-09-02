<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_create_collect_with extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create_collect_with()
	{
		if(!$this->input['collect_name'])
		{
			$this->errorOutput();
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."vod_collect  SET  ";
		$sql.= "collect_name='".urldecode($this->input['collect_name'])."',".
		       "vod_sort_id='".urldecode($this->input['sort_name'])."',". 
		       "source='".urldecode($this->input['source'])."',". 
		       "admin_name='".urldecode($this->user['user_name'])."',". 
		       "admin_id='".urldecode($this->user['user_id'])."',". 
			   "create_time='".TIMENOW."',".
           	   "update_time='".TIMENOW."'";
		
		$this->db->query($sql);
		$collect_id = $this->db->insert_id();
		/*更新collect_order_id*/
		$sql = 'UPDATE '.DB_PREFIX.'vod_collect SET collect_order_id='.$collect_id.' WHERE id='.$collect_id;
		$this->db->query($sql);
		/*添加集合成功之后，要更新该集合所属的类别的表里面的collect_count*/
		$sql = "UPDATE ".DB_PREFIX."vod_sort SET collect_count = collect_count + 1 WHERE id = ".urldecode($this->input['sort_name']);
		$this->db->query($sql);
		
		
		/*创建好集合之后,将选择的视频添加进该集合中*/
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
		
		$this->addItem($collect_id);
		$this->output();	
	}
	
	public function  collect_info()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vod_collect WHERE id = ".urldecode($this->input['id']);
		$return = $this->db->query_first($sql);
		
		/*查询出来源*/
		$sql = "SELECT name as sort_name FROM ".DB_PREFIX."vod_media_node WHERE id = '".$return['vod_sort_id']."'";
		$sort = $this->db->query_first($sql);
		if($sort['sort_name'])
		{
			$return['vod_sort_id'] = $sort['sort_name'];
		}
		else 
		{
			$return['vod_sort_id'] = '';
		}
		
		$return['relate_module_id'] = intval($this->input['relate_module_id']);
		$return['update_time'] = date('Y-m-d h:i',$return['update_time']);
		$return['create_time'] = date('Y-m-d h:i',$return['create_time']);
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new vod_create_collect_with();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create_collect_with';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>