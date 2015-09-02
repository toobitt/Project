<?php
class project_list_mode extends InitFrm
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
		$sql = "SELECT id,dates,user_name,create_time,status,order_id FROM " . DB_PREFIX . "project  WHERE 1 " . $condition . $orderby . $limit;
		//echo $sql;exit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array(),$table_name = '')
	{
		if(!$data)
		{
			return false;
		}
		if(!$table_name)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . $table_name . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX.$table_name." SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array(),$table_name = '')
	{
		if(!$data || !$id)
		{
			return false;
		}
		if(!$table_name)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. $table_name." WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . $table_name." SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($cinema_id = '', $movie_id = '', $create_time = '')
	{
		if(!$cinema_id)
		{
			return false;
		}
		if(!$movie_id)
		{
			return false;
		}
		if(!$create_time)
		{
			return false;
		}
		$sql = "SELECT id,movie_name FROM " .DB_PREFIX. "project WHERE cinema_id = " .$cinema_id. " AND movie_id = " .$movie_id. " AND dates = '" .$create_time. "'";
		$project = $this->db->query_first($sql);
		if(!$project)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "project_list WHERE project_id = " .$project['id']. " ORDER BY project_time ASC";
		$query = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($query))
		{
			$time = strtotime(date('Y-m-d',$row['project_time']).' 12:00');
			if($row['project_time'] < $time)
			{
				$row['project_time'] = date('H:i',$row['project_time']);
				$ret['project_list']['am'][] = $row;
			}
			else 
			{
				$row['project_time'] = date('H:i',$row['project_time']);
				$ret['project_list']['pm'][] = $row;
			}
		}
		//查出影片信息
		$sql = "SELECT index_pic FROM " .DB_PREFIX. "movie WHERE id = " .$movie_id;
		$movie_info = $this->db->query_first($sql);
		$ret['movie'] = array(
			'movie_id' => $movie_id,
			'movie_name' => $project['movie_name'],
			'movie_img' => unserialize($movie_info['index_pic']),
		);
		$ret['cinema'] = array(
			'cinema_id' => $cinema_id,
			'cinema_name' => $this->input['cinema_name'],
		);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $ret;
	}
	
	public function count($condition = '',$orderby = '',$limit = '')
	{
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "project  WHERE 1 " . $condition . $limit;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "project WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "project_list WHERE project_id IN (" . $id . ")";
		$this->db->query($sql);
		$sql = " DELETE FROM " .DB_PREFIX. "project WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$audit)
	{
		if(!$id)
		{
			return false;
		}
		switch ($audit)
		{
			case 1:$status = 1;$audit_status = '已审核';break;//审核
			case 0:$status = 2;$audit_status = '已打回';break;//打回
		}
		
		$sql = " UPDATE " .DB_PREFIX. "project SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'audit'=>$audit_status);
	}
}
?>