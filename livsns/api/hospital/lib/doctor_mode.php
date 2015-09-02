<?php
class doctor_mode extends InitFrm
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
		
		$sql = "SELECT t1.id,t1.title,t1.name,t1.doctor_id,t1.department_id,t1.hospital_id,t1.speciality,t3.host,t3.dir,t3.filepath,t3.filename FROM " . DB_PREFIX . "doctor t1 
				LEFT JOIN " . DB_PREFIX . "materials t3
					ON t1.indexpic_id = t3.id
				WHERE 1 " . $condition . $orderby . $limit; 
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q)) 
		{	
			$row['level'] = $this->settings['doctor_level'][$row['level']];
			
			if($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
			{
				$row['indexpic'] = array(
					'host'		=> $row['host'],
					'dir'		=> $row['dir'],
					'filepath'	=> $row['filepath'],
					'filename'	=> $row['filename'],
				);
			}
			else 
			{
				$row['indexpic'] = array();
			}
			unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
			$info[] = $row;
		}
		
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "doctor SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."doctor SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "doctor WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "doctor SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "doctor  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		
		if($info['indexpic_id'])
		{
			$sql = "SELECT host,dir,filepath,filename FROM " . DB_PREFIX . "materials WHERE id = {$info['indexpic_id']}";
			$mater_res = $this->db->query_first($sql);
			
			if(!empty($mater_res))
			{
				$info['indexpic'] = array(
					'host'		=> $mater_res['host'],
					'dir'		=> $mater_res['dir'],
					'filepath'	=> $mater_res['filepath'],
					'filename'	=> $mater_res['filename'],
				);
			}
			else 
			{
				$info['indexpic'] = array();
			}
		}
		
		if($info['department_id'] && $info['hospital_id'])
		{
			$sql = "SELECT name FROM " . DB_PREFIX . "departments WHERE hospital_id = " . $info['hospital_id'] . " AND department_id = " . $info['department_id'];
			$res = $this->db->query_first($sql);
			
			$info['department_name'] = $res['name'];
			
			$sql = "SELECT * FROM " . DB_PREFIX . "schedules WHERE hospital_id = {$info['hospital_id']} AND department_id = {$info['department_id']} AND doctor_id = {$info['doctor_id']}";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$r['reg_time'] = $this->settings['regTime'][$r['reg_time']];
				$r['call_type'] = $this->settings['CallType'][$r['call_type']];
				$schedules[] = $r;
			}
			
			if(!empty($schedules))
			{
				$info['schedules_info'] = $schedules;
			}
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "doctor WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "doctor WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		return $id;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "doctor WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "doctor SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	//插入素材表
	public function insert_material($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	//更新素材表
	public function update_material($cid,$data)
	{
		if (!is_array($data) || !$data || !$cid)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= " WHERE id = {$cid}";
		$this->db->query($sql);
	}
}
?>