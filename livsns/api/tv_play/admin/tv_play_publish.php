<?php
define('MOD_UNIQUEID','tv_play_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class TvPlayPublish extends appCommonFrm implements publish
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM "  . DB_PREFIX . "tv_play WHERE id = '" .$id. "'";
		}
		else
		{
			/*
			$sql = "SELECT tp.*,te.img AS episode_img FROM " . DB_PREFIX ."tv_play tp 
					LEFT JOIN ". DB_PREFIX."tv_episode te 
						ON te.tv_play_id = tp.id
					WHERE 1 AND t.play_sort_id = {$sort_id}";
			*/
			$sql = "SELECT * FROM "  . DB_PREFIX . "tv_play WHERE play_sort_id = '" .$sort_id. "'";
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$sql = "SELECT count(*) AS total FROM ".DB_PREFIX."tv_episode WHERE tv_play_id = '".$id."'";
			$episode_arr = $this->db->query_first($sql);
			$row['content_fromid'] = $row['id'];
			$img_info =  unserialize($row['img']);
			$row['indexpic'] =$img_info;
			$row['ip'] = hg_getip();
			$row['user_id'] = $row['user_id'];
            $row['user_name'] = $row['user_name'];
			$row['child_num'] = $episode_arr['total'];
			$row['special'] = $row['special']?unserialize($row['special']):array();
			$row['comment_num'] = $row['comm_num'];
			unset($row['id']);
			$ret[] = $row;
		}
		
		$this->addItem($ret);
		$this->output();		
	}
 	
 	/**
 	 * 更新内容expand_id,发布内容id
 	 *
 	 */
 	function update_content()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."tv_play WHERE id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if(intval($ret['status']) != 2)
		{
			$sql = "UPDATE " . DB_PREFIX ."tv_play SET expand_id = 0,column_url = '' WHERE id = " . $data['from_id'];
		}	
		else 
		{
			$column_id = unserialize($ret['column_id']);	   //发布栏目	
			$column_url = unserialize($ret['column_url']);	   //栏目url，发布对比，有删除栏目则删除对于栏目url
			$url = array();
			if(!empty($column_url) && is_array($column_url))
			{
				foreach($column_url as $k => $v)
				{
					if($column_id[$k])
					{
						$url[$k] = $v;
					}
				}
			}
			if(!empty($data['content_url']) && is_array($data['content_url']))
			{
				foreach($data['content_url'] as $k => $v)
				{
					$url[$k] = $v;
				}
			}
			$sql = "UPDATE " . DB_PREFIX . "tv_play SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' WHERE id = " . $data['from_id'];
		}
		$this->db->query($sql);
		if(empty($data['expand_id']))
		{
			$sql = "UPDATE " . DB_PREFIX. "tv_episode SET expand_id = " . $data['expand_id'] . " WHERE tv_play_id =" . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output(); 		
 	}
 	
 	/**
 	 * 删除这条内容的发布
 	 *
 	 */
 	function delete_publish()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		if($data['is_delete_column'])   //只删除某一栏目中内容
		{
			$sql = "SELECT column_id,column_url FROM " . DB_PREFIX ."tv_play WHERE id = " . $data['from_id'];
			$ret = $this->db->query_first($sql);
			$column_id = unserialize($ret['column_id']);
			$column_url = unserialize($ret['column_url']);
			$del_columnid = explode(',',$data['column_id']);
			if(is_array($del_columnid))
			{
				foreach($del_columnid as $k => $v)
				{
					unset($column_id[$v],$column_url[$v]);	
				}
			}
			$sql = "UPDATE " . DB_PREFIX ."tv_play 
					SET column_id = '".addslashes(serialize($column_id))."',column_url = '".addslashes(serialize($column_url))."' 
					WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "tv_play 
					SET expand_id = '', column_id = '', column_url = '' 
					WHERE id = " . $data['from_id'];
			$this->db->query($sql);	
			$sql = "UPDATE " . DB_PREFIX . "tv_episode 
					SET expand_id = '' 
					WHERE tv_play_id = " . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('true');
		$this->output(); 		
 	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}
$out = new TvPlayPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
$out->$action(); 
?>
