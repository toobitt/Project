<?php
class mood_style_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_option  WHERE 1";
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query))
		{
			if($r['picture'])
			{
				$r['picture'] = @unserialize($r['picture']);
			}
			$option[$r['style_id']][] = array(
			    'id'    => $r['id'],
			    'name'  => $r['mood_name'],
			    'picture' => hg_material_link($r['picture']['host'],$r['picture']['dir'],$r['picture']['filepath'],$r['picture']['filename']),
			    'order_id'  => $r['order_id'],
			);
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_style  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['index_picture'])
			{
				$r['index_picture'] = @unserialize($r['index_picture']);
			    $r['index_picture'] = hg_material_link($r['index_picture']['host'],$r['index_picture']['dir'],$r['index_picture']['filepath'],$r['index_picture']['filename']);
			}
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s',$r['update_time']);
			$r['audit_time'] = date('Y-m-d H:i:s',$r['audit_time']);
			$r['option'] = $option[$r['id']];
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "mood_style SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."mood_style SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "mood_style WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "mood_style SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$data['id'] = $id;	
		
		if ($id)
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($sql))
			{
				$data['affected_rows'] = 1;
			}
		}
		return $data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_style  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info['index_picture'])
		{
			$info['index_picture'] = @unserialize($info['index_picture']);
			$info['index_picture'] = hg_material_link($info['index_picture']['host'],$info['index_picture']['dir'],$info['index_picture']['filepath'],$info['index_picture']['filename']);
		}
		$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
		$info['update_time'] = date('Y-m-d H:i:s',$info['update_time']);
		$info['audit_time'] = date('Y-m-d H:i:s',$info['audit_time']);
		$option = $this->get_options($id);
		$info['option'] = $option;
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mood_style WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "mood_style WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		$sql = " SELECT * FROM " .DB_PREFIX. "mood_option WHERE style_id IN (" . $id . ")";
		$qs = $this->db->query($sql);
		while ($r = $this->db->fetch_array($qs))
		{
			$pre_data['option'] = $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "mood_style WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//删除心情选项表
		$sql = " DELETE FROM " .DB_PREFIX. "mood_option WHERE style_id IN (" . $id . ")";
		$this->db->query($sql);
		return $id;
	}
	
	public function audit($ids = '',$pre_status)
	{
		if(!$ids)
		{
			return false;
		}
	    switch (intval($pre_status))
		{
			case 0:$status = 2;break;//审核
			case 1:$status = 1;break;//审核
			//case 2:$status = 1;break;//审核
		}
		
		$update_data = array(
		    'status'    => $status,
		    'audit_user_id'    => $this->user['user_id'],
		    'audit_user_name'  => $this->user['audit_user_name'],
		    'audit_time'    => TIMENOW,
		    );
		$sql = " UPDATE " . DB_PREFIX . "mood_style SET ";
		foreach ($update_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id IN (".$ids. ")";
		$this->db->query($sql);
		$ret['id'] = $ids;
		$ret['status'] = $status;
		return $ret;
	}
	
	public function get_options($style_id)
	{
		if(!$style_id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_option  WHERE style_id = '" .$style_id. "'  ORDER BY order_id ASC, id ASC";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if($r['picture'])
			{
				$r['picture'] = @unserialize($r['picture']);
			}
			$option[] = array(
			    'id'    => $r['id'],
			    'name'  => $r['mood_name'],
			    'picture' => hg_material_link($r['picture']['host'],$r['picture']['dir'],$r['picture']['filepath'],$r['picture']['filename']),
			    'order_id'  => $r['order_id'],
			);
		}
		return $option;	
	}
	
	public function delete_options($ids)
	{
		if(!$ids)
		{
			return false;
		}
		$sql = "DELETE FROM " . DB_PREFIX . "mood_option  WHERE id IN (" .$ids. ")";
		$query = $this->db->query($sql);
		return $ids;
	}
}
?>