<?php
class announcement_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$limit)
	{
		//查询出所有区域
		$sql = "SELECT * FROM " .DB_PREFIX. "carpark_district";
		$q = $this->db->query($sql);
		$district = array();
		while($row = $this->db->fetch_array($q))
		{
			$district[$row['id']] = $row['name'];
		}
		
		$sql = "SELECT a.*,c.name AS carpark_name,c.district_id FROM " . DB_PREFIX . "announcement a LEFT JOIN " .DB_PREFIX. "carpark c ON a.carpark_id = c.id WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['status'] 		= $this->settings['announcement_status'][$r['status']];
			$r['create_time'] 	= date('Y-m-d H:i',$r['create_time']);
			$r['district_name'] = $district[$r['district_id']];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "announcement SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		//创建完之后更新该公告对应的停车场的公告数量
		if($data['carpark_id'])
		{
			$sql = " UPDATE ".DB_PREFIX."carpark SET announce_num = announce_num + 1  WHERE id = '" .$data['carpark_id']. "'";
			$this->db->query($sql);
		}
		$sql = " UPDATE ".DB_PREFIX."announcement SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return true;
	}
	
	public function update($data = array(),$id,$is_first = 0)
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "announcement WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		$sql = " UPDATE " . DB_PREFIX . "announcement SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		if(!$this->db->affected_rows())
		{
			return false;
		}
		//此处更新停车场里面的公告的数量
		if($is_first)
		{
			//只有在原来的停车场id与更新之后的停车场id不同的情况下才会去更新
			if($pre_data['carpark_id'] != $data['carpark_id'])
			{
				if($pre_data['carpark_id'])
				{
					$sql = " UPDATE ".DB_PREFIX."carpark SET announce_num = announce_num - 1  WHERE id = '" .$pre_data['carpark_id']. "'";
					$this->db->query($sql);
				}
				
				if($data['carpark_id'])
				{
					$sql = " UPDATE ".DB_PREFIX."carpark SET announce_num = announce_num + 1  WHERE id = '" .$data['carpark_id']. "'";
					$this->db->query($sql);
				}
			}
		}
		return $pre_data;
	}
	
	public function detail($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "announcement WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "announcement a WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($condition = '')
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "announcement WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$pre_data = array();
		$ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
			$ids[] = $r['id'];
		}
		if(!$pre_data)
		{
			return false;
		}
		$sql = " DELETE FROM " .DB_PREFIX. "announcement WHERE 1 " . $condition;
		$this->db->query($sql);
		//更新对应停车场的数量
		foreach($pre_data AS $k => $v)
		{
			if($v['carpark_id'])
			{
				$sql = " UPDATE ".DB_PREFIX."carpark SET announce_num = announce_num - 1  WHERE id = '" .$v['carpark_id']. "'";
				$this->db->query($sql);
			}
		}
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "announcement WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "announcement SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $this->settings['announcement_status'][$status],'id' => $id);
	}
	
	//根据地区获取公告
	public function get_announcement_by_district($district_id = '',$orderby,$limit)
	{
		//查询出城市id
		$sql = "SELECT * FROM " .DB_PREFIX. "carpark_district WHERE id = '" .$district_id. "'";
		$area_arr = $this->db->query_first($sql);
		if(!$area_arr)
		{
			return false;
		}
		
		//查询出公告
		$sql = "SELECT a.*,c.name,c.district_id FROM " .DB_PREFIX. "announcement a LEFT JOIN " .DB_PREFIX. "carpark c ON a.carpark_id = c.id WHERE c.status=2 AND c.district_id = '" .$area_arr['id']. "' " .$orderby . $limit;
		$q = $this->db->query($sql);
		$announcement = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['district_name'] = $area_arr['name'];
			$announcement[] = $r;
		}
		return $announcement;
	}
}
?>