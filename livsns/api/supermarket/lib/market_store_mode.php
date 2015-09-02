<?php
class market_store_mode extends InitFrm
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
		$sql = "SELECT ms.*,m.img_info FROM " . DB_PREFIX . "market_store ms LEFT JOIN " .DB_PREFIX. "material m ON m.id = ms.index_pic  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['pics'] = $this->get_logo_arr($r['logo_id']);
			$r['index_pic'] = unserialize($r['img_info']);
			unset($r['img_info']);
			if($r['tel'])
			{
				$r['tel'] = explode(',',$r['tel']);
			}
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "market_store SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."market_store SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		$data['id'] = $vid;
		return $data;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "market_store WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "market_store SET ";
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
		
		$sql = "SELECT ms.*,m.img_info FROM " . DB_PREFIX . "market_store ms LEFT JOIN " .DB_PREFIX. "material m ON m.id = ms.index_pic WHERE ms.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info['tel'])
		{
			$info['tel'] = explode(',',$info['tel']);
		}
		$info['logo'] = $this->get_logo($info['logo_id']);
		$info['index_pic_id'] = $info['index_pic'];
		$info['index_pic'] = hg_fetchimgurl(unserialize($info['img_info']));
		$info['create_time'] = date('Y-m-d H:m:s',$info['create_time']);
		$info['update_time'] = date('Y-m-d H:m:s',$info['update_time']);
		
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "market_store ms WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "market_store WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "market_store WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	public function get_logo($ids)
	{
		if($ids)
		{
		    $sql = " SELECT * FROM " .DB_PREFIX. "material WHERE id IN (" . $ids . ")";
		    $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
		    {
			    $pre_data[] = $r['img_info'];
		    }
		    if(is_array($pre_data))
		    {   
		    	foreach ($pre_data as $key => $val)
			    {
				    $imginfo[] = hg_fetchimgurl(unserialize($val));
			    }
		    }
			if(count($imginfo) == 1)
			{
				$imginfo = $imginfo[0];
			}
			return $imginfo;
		}
		else
		{
			return null;
		}
	}
	
	public function get_logo_arr($ids = '')
	{
		$img_info = array();
		if(!$ids)
		{
			return $img_info;
		}
	 	$sql = " SELECT * FROM " .DB_PREFIX. "material WHERE id IN (" . $ids . ")";
	    $q = $this->db->query($sql);
	    
        while ($r = $this->db->fetch_array($q))
	    {
		    $img_info[] = unserialize($r['img_info']);
	    }
	    return $img_info;
	}
	
	public function delete_img($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "material WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		elseif(is_array($pre_data))

		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "material WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}

}
?>