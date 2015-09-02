<?php
class body_tpl_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "content_tpl  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['status_text'] = $this->settings['body_tpl_status'][$r['status']];
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['type_text'] = $this->settings['body_tpl_type'][$r['type']];
			if($r['img_info'])
			{
				$r['img_info'] = @unserialize($r['img_info']);
			}
			else 
			{
				$r['img_info'] = array();
			}
			
			if($r['img_info'] && is_array($r['img_info']))
			{
				$r['img_url'] = $r['img_info']['host'] . $r['img_info']['dir'] . $r['img_info']['filepath'] . $r['img_info']['filename'];
			}
			else 
			{
				$r['img_url'] = '';
			}
			
			if($r['body_html'])
			{
				$r['html_str'] = html_entity_decode($r['body_html']);
			}
			else 
			{
				$r['html_str'] = '';
			}
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "content_tpl SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."content_tpl SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "content_tpl WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "content_tpl SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "content_tpl  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info['img_info'])
		{
			$info['img_info'] = @unserialize($info['img_info']);
		}
		else 
		{
			$info['img_info'] = array();
		}
		
		if($info['body_html'])
		{
			$info['html_str'] = html_entity_decode($info['body_html']);
		}
		else 
		{
			$info['html_str'] = '';
		}

		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "content_tpl WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "content_tpl WHERE id IN (" . $id . ")";
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
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "content_tpl WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "content_tpl WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "content_tpl SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'status_text' => $this->settings['body_tpl_status'][$status]);
	}
	
	//获取正文模板信息
	public function getTplInfo($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " . DB_PREFIX . "content_tpl  WHERE 1 " . $cond;
		$info = $this->db->query_first($sql);
		if($info)
		{
    		if($info['img_info'] && @unserialize($info['img_info']))
    		{
    			$info['img_info'] = @unserialize($info['img_info']);
    		}
    		else 
    		{
    			$info['img_info'] = array();
    		}
    		return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
}