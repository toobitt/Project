<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: publish.class.php 735 2012-05-21 05:51:07Z zhuld $
***************************************************************************/
include_once(ROOT_DIR.'lib/class/queue.class.php');
class publish
{
	private $db;
	private $user;
	private $queue;
	function __construct()
	{
		global $_INPUT, $gUser;
		$this->queue = new queue();
		$this->user = $gUser;
		$this->db = hg_checkDB();
	}
	function __destruct()
	{	
	}
	function get_column_siteid($columns=NULL)
	{
		$return = array();
		if(empty($columns[0]) || !$columns)
		{
			return $return;
		}
		$columns = is_array($columns) ? implode(',', $columns) : $columns;
		$sql = 'SELECT siteid,id FROM '.DB_PREFIX.'columns WHERE id IN('.$columns.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$return[$row['id']] = $row['siteid']; 
		}
		return $return;
	}
	//参数依次是站点ID 模块ID 内容ID 选择的栏目 发布内容的状态 1--打回，审核未通过 0--删除
	function update(/*$siteid=1, */$moduleid = 0, $conid = 0, $new_colid=array(), $status=0 ,$admin_info = array())
	{
		if(!is_array($new_colid))
		{
			$new_colid = explode(',', $new_colid);
		}
		if(!$moduleid || !$conid)
		{
			return false;
		}
		
		//获取栏目所属站点ID
		//return $this->get_column_siteid($new_colid);
		if($siteids = $this->get_column_siteid($new_colid))
		{
			$_siteids = array_unique($siteids);
		}
		//$col_mapid = unserialize($col_mapid);
		$sql = 'SELECT colid FROM '.DB_PREFIX.'publish WHERE 1 AND mid = '.intval($moduleid).' AND conid = '.intval($conid);
		$old_colid = array();
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$old_colid[] = $row['colid'];
		}

		if(empty($old_colid) && empty($new_colid))
		{
			return false;
		}

		$diff_new = array_unique(array_diff($new_colid, $old_colid));//新增发布的栏目
		//print_r($diff_new);exit;
		if($diff_new)
		{
			$pid = $space = "";
			foreach($diff_new as $colid)
			{
				if($colid && $siteids[$colid])
				{
					$sql = 'INSERT INTO '.DB_PREFIX.'publish SET ';
					$sql .= 'siteid = "'.$siteids[$colid].'", ';
					$sql .= 'mid = "'.$moduleid.'", ';
					$sql .= 'conid = "'.$conid.'", ';
					$sql .= 'colid = "'.$colid.'", ';
					$sql .= 'status = 0, ';
					$sql .= 'cms_contentmap_id = 0, ';
					$sql .= 'ip = "'.hg_getip().'", ';
					//此处的发布时间修改为计划任务执行返回contentmap_id时在入库
					//$sql .= 'pub_time = "'.TIMENOW.'",';
					$sql .= 'user_name = "' . $admin_info['admin_name'] . '"';
					$this->db->query($sql);
					$pid .= $space . $this->db->insert_id();
					$space = ",";
				}
			}
			$this->queue->create($pid, 0);
		}

		$diff_old = array_unique(array_diff($old_colid, $new_colid));//删除原有的发布的栏目，
		if(!empty($diff_old))
		{
			$update_colid = implode(',', $diff_old);

			$sql =  'SELECT pubid,cms_contentmap_id FROM '.DB_PREFIX.'publish WHERE colid IN (' . $update_colid . ') AND mid=' . intval($moduleid) . ' AND conid = ' . intval($conid); 
			$q = $this->db->query($sql);
			$pubid = $space = $pubid_del = $space_del = "";
			while($row = $this->db->fetch_array($q))
			{
				if($row['cms_contentmap_id'])//已发布的，判断，status 存在即是打回，不存在即是删除
				{
					$pubid_del .= $space_del . $row['pubid'];
					$space_del = ",";
				}
				else //未发布的直接删除
				{
					$pubid .= $space . $row['pubid'];
					$space = ",";
				}
			}

			if($pubid)
			{
				$sql = "DELETE FROM " . DB_PREFIX . "publish WHERE pubid IN(" . $pubid . ")";
				$this->db->query($sql);
				$this->queue->delete($pubid);
			}
			if($pubid_del)
			{
				if($status)
				{
					$sql = "UPDATE " . DB_PREFIX . "publish SET status=0 WHERE pubid IN(" . $pubid_del . ")";//打回，审核不通过
					$this->db->query($sql);
				}
				$this->queue->create($pubid_del, 1);
			}			
		}

		$diff_same = array_unique(array_uintersect($new_colid,$old_colid,"strcasecmp"));//交集，重新插入队列
		if(!empty($diff_same))
		{
			$update_colid = implode(',', $diff_same);
			$sql =  'SELECT pubid,cms_contentmap_id FROM '.DB_PREFIX.'publish WHERE colid IN (' . $update_colid . ') AND mid=' . intval($moduleid) . ' AND conid = ' . intval($conid); 
			$q = $this->db->query($sql);
			$pubid = $space = "";
			while($row = $this->db->fetch_array($q))
			{
				$pubid .= $space . $row['pubid'];
				$space = ",";
			}
			if($pubid)
			{
				$this->queue->create($pubid, 0);
			}
		}
		return true;
	}
	//列表读取发布数据方法
	function getPublishedCol($moduleid = 0, $id=array())
	{
		$return = array();
		if(empty($moduleid) || empty($id))
		{
			return $return;
		}
		$ids = implode(',', $id);
		if(!$ids)
		{
			return false;
		}
		$sql = 'SELECT p.conid,c.type,c.name,c.id FROM '.DB_PREFIX.'publish p LEFT JOIN '.DB_PREFIX.'columns c ON p.colid = c.id  WHERE p.conid IN(' . $ids . ') AND p.mid = '.intval($moduleid);
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$_types = explode(',', $r['type']);
			if($_types)
			{
				foreach($_types as $k=>$v)
				{
					$return[$r['conid']][$v][$r['id']] = $r['name'];
				}
			}
		}
		return $return;
	}

	/**
		$status 1--发布 2--删除 0--待发布（更新中不进行调用）
	*/
	function clear_cms($pid)
	{
		if(!$pid)
		{
			return false;
		}

		$sql = "UPDATE " . DB_PREFIX . "publish SET cms_contentmap_id='',status=0 where pubid=" . $pid;
		$this->db->query($sql);
		$this->update_time($pid);
	}

	function update_time($pid)
	{
		if(!$pid)
		{
			return false;
		}
		$this->queue->update_time($pid);		
	}
	
	function update_cms_contentmap($pid,$cms_contentmap_id=0,$status=0, $pub_time='')
	{
		if(!$pid)
		{
			return false;
		}
		//在第一次发布时 记录发布时间
		if($pub_time)
		{
			$sql = 'UPDATE '.DB_PREFIX.'publish SET pub_time = "'.$pub_time.'" WHERE pub_time = 0 AND pubid ='.$pid;
			$this->db->query($sql);
		}

		$sql = "UPDATE " . DB_PREFIX . "publish SET status=" . $status . ",cms_contentmap_id=" . $cms_contentmap_id . " where pubid=" . $pid;
		$this->db->query($sql);
		$this->queue->delete($pid);
	}


	/*频道发布*/
	function get_publish_col($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'columns WHERE source = '.intval($id);
		$q = $this->db->query($sql);
		$publish_col = array();
		while($row = $this->db->fetch_array($q))
		{
			$publish_col[] = $row['fatherid'];
		}
		return $publish_col;
	}

	function del($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'publish WHERE pubid = '.intval($id);
		$q = $this->db->query($sql);
		$this->queue->delete($id);
	}

	//获取内容已发布的栏目 读取发布表 无需添加任何过滤条件
	function getcontentpub($mid, $contentid)
	{
		$flag = false;
		if(is_array($contentid) && $contentid)
		{
			$flag = true;
			$contentid = implode(',', $contentid);
		}
		else
		{
			$contentid = intval($contentid);
		}
		$sql ='SELECT conid,colid FROM '.DB_PREFIX.'publish WHERE mid='.intval($mid).' AND conid IN('.$contentid.')';
		$q = $this->db->query($sql);
		$colid = array();
		while($row = $this->db->fetch_array($q))
		{
			$colid[$row['conid']][] = $row['colid'];
		}
		//单内容
		if(!$flag)
		{
			return $colid[$contentid]; 
		}
		//多内容多数组返回
		return $colid;
	}
	//统计单个模块的发布数据总和
	function get_module_pub_total($module_id, $subcolumn=0,$type = 1)
	{
		$sql = "SELECT count(*) AS total FROM ".DB_PREFIX."publish p LEFT JOIN ".DB_PREFIX."columns c ON p.colid = c.id LEFT JOIN ".DB_PREFIX."column_type_map col_map ON c.id=col_map.columnid WHERE p.mid=".intval($module_id)." AND col_map.column_flag=".$type."";
		if($subcolumn)
		{
			$sql .= " AND p.colid IN({$subcolumn})";
		}
		return $total = $this->db->query_first($sql);
	}
	//获取特定的模块的分页发布数据
	function get_module_pub_data($module_id, $page=0, $count=20, $subcolumn='', $node_type=1)
	{
		$sql = "SELECT p.conid,c.id FROM ".DB_PREFIX."publish p LEFT JOIN ".DB_PREFIX."columns c ON p.colid = c.id LEFT JOIN ".DB_PREFIX."column_type_map col_map ON c.id=col_map.columnid WHERE p.mid=".$module_id." AND col_map.column_flag=".$node_type."";
		if($subcolumn)
		{
			$sql .= " AND p.colid IN({$subcolumn})";
		}
		$sql .= " ORDER BY p.pub_time DESC LIMIT {$page}, {$count}";
		$q = $this->db->query($sql);
		$condis = array();
		while($r = $this->db->fetch_array($q))
		{
			$conids["conid"][] = intval($r["conid"]);
			$conids["cid"][] = intval($r["id"]);
		}
		return $conids;
	}
}
?>