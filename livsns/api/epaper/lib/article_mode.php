<?php
class article_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT id,title,user_id,org_id,epaper_id FROM " . DB_PREFIX . "article  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array(),$content = "")
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "article SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		
		$vid = $this->db->insert_id();
		$data['id'] = $vid;
		
		
		$sql = " UPDATE ".DB_PREFIX."article SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		
		if($vid && $content)
		{
			$sql = " INSERT INTO " . DB_PREFIX . "article_content SET articleid = {$vid},content = '" . $content . "'";
			$this->db->query($sql);
			$data['content'] = $content;
		}
		return $data;
	}
	
	public function update($id,$data = array(),$content)
	{
		
		if(!$data || !$id)
		{
			return false;
		}
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "article SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if($id && $content)
		{
			$sql = " UPDATE " . DB_PREFIX . "article_content SET content = '" . $content . "' WHERE articleid = " . $id;
			$this->db->query($sql);
			$data['content'] = $content;
		}
		return $data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT a.*,c.content FROM " . DB_PREFIX . "article a 
				LEFT JOIN ".DB_PREFIX."article_content c 
					ON a.id = c.articleid 
				WHERE a.id = " .$id;
		$info = $this->db->query_first($sql);
		
		
		$ret = $this->getMaterialById($id);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{		
				$v['filesize'] = hg_bytes_to_size($v['filesize']);
		        switch($v['mark'])
			    {
					case 'img':
						//将缩略图信息加入info数组
						$info['material'][$v['id']] = $v;
						$info['material'][$v['id']]['path'] = $v['host'] . $v['dir'];
						$info['material'][$v['id']]['dir'] = $v['filepath'];	
						$info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);											
						break;
					case 'doc':
						$info['material'][$v['id']] = $v;
						$info['material'][$v['id']]['path'] = $v['host'] . $v['dir'];
						$info['material'][$v['id']]['dir'] = $v['filepath'];
						$info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
						break;
					case 'real':
						$info['material'][$v['id']] = $v;
						break;
					default:
						break;
			   }
			}
		}
		
		
		/*$period_id = $info['period_id'];
		$stack_id = $info['stack_id'];
		$page_id = $info['page_id'];
		
		
		if($period_id && $page_id && $stack_id)
		{
			$info['stack'] = $this->settings['stack_set'][$stack_id];
			$sql = "SELECT id FROM ".DB_PREFIX."page WHERE period_id = ".$period_id;
			$q = $this->db->query($sql);
			$page_num = 1;
			while ($r = $this->db->fetch_array($q))
			{
				if($r['id'] == $page_id)
				{
					$info['page_num'] = $this->settings['stack_set'][$stack_id].$page_num;
					break;
				}
				$page_num = $page_num + 1;
			}
		}*/
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "article WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		
		$page_id = $pre_data[0]['page_id'];
		if($page_id)
		{
			$sql = "SELECT hot_area FROM ".DB_PREFIX."page WHERE id = ".$page_id;
			$res = $this->db->query_first($sql);
			
			$hot_area = unserialize($res['hot_area']);
			$art_ids = explode(',', $id);
			if($hot_area && $art_ids)
			{
				foreach ($hot_area as $k => $v)
				{
					if(in_array($v['id'], $art_ids))
					{
						continue;
					}
					$new_hot_area[] = $v;
				}
			}
			if($new_hot_area)
			{
				$new_hot_area = serialize($new_hot_area);
			}
			else 
			{
				$new_hot_area = '';
			}
			$sql = "UPDATE ".DB_PREFIX."page SET hot_area = '".$new_hot_area."'  WHERE id  = ".$page_id;
			$this->db->query($sql);
			
		}
		//删除主表
		$sql = "DELETE FROM " .DB_PREFIX. "article WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " .DB_PREFIX. "article_content WHERE articleid IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "article WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "article SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//更新期下叠数，页数
	public function update_period($id)
	{
		if(!$id)
		{
			return false;
		}
		//查询有多少叠和版
		$sql = "SELECT id,stack_id FROM ".DB_PREFIX."page WHERE period_id = ".$id;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$page_ids[] = $r['id'];
			//$stack_arr[$r['stack_id']] = 1;
		}
		
		//统计叠数
		//$stack_num = count($stack_arr);
		
		//统计叠
		$sql = 'SELECT count(*) as total FROM ' . DB_PREFIX . "stack WHERE period_id = " . $id;
		$count = $this->db->query_first($sql);
		$stack_num = $count['total'];
		
		//统计版数
		$page_count = count($page_ids);
		
		//更新期下叠数和页数
		$sql = "UPDATE ".DB_PREFIX."period SET stack_num = ".$stack_num.",page_num = ".$page_count." WHERE id = ".$id;
		$this->db->query($sql);
	}
	
	//根据内容id取素材
	public function getMaterialById($cid)
	{	
		if(!$cid)
			return false;
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE cid=" . $cid . " AND isdel=1"; //1表示没删除
		$q = $this->db->query($sql);
		$info = array();
		while(false != ($ret = $this->db->fetch_array($q)))
		{
			if(empty($ret))
			{
				continue;
			}
			switch($ret['mark'])
			{
				case 'img':
					if($ret['pic'])
					{
						$ret['pic'] = unserialize($ret['pic']);
					}
					$info[$ret['material_id']] = $ret;
					$info[$ret['material_id']]['url'] = hg_fetchimgurl($ret['pic'],100,75);
					break;
				case 'doc':
					$info[$ret['material_id']] = $ret;
					break;
				default:
					break;
			}
		}
		return $info;
	}
	
	public function insert_data($data,$table)
	{
		if(!$table || empty($data))
		{
			return false;
		}
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		return $this->db->insert_id();		
	}
	
}
?>