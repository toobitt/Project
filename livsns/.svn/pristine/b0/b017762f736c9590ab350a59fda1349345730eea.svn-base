<?php
class ClassgatherSet extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT g.*,s.name AS sort_name FROM '.DB_PREFIX.'gather_set g
				LEFT JOIN '.DB_PREFIX.'sort s ON g.sort_id = s.id 
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			switch ($row['is_open'])
			{
				case 0: $row['open_status'] = '未开启';break;
				case 1: $row['open_status'] = '已开启';break;
			}
			$data[] = $row;
		}
		return $data;
	}
	
	public function detail($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather_set WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if (empty($ret))
		{
			return false;
		}
		if ($ret['parameter'])
		{
			$ret['parameter'] = unserialize($ret['parameter']);
		}
		return $ret;
	}
	/**
	 * 
	 * @Description form页面关联数据
	 * @author Kin
	 * @date 2013-8-27 下午03:00:21
	 */
	public function show_sort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort';
		$query = $this->db->query($sql);
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[$row['id']] = $row['name'];
		}
		return $sorts;
	}
	public function get_gathers_set()
	{
		$sql = "select * from " . DB_PREFIX . "gather_set ";
		$q = $this->db->query($sql);
		$info = $this->db->fetch_array($q);
		return $info;
	}
	
	public function create($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO ' . DB_PREFIX. 'gather_set SET ';		
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",'; 
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'gather_set SET order_id = '.$id.' WHERE id ='.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;		
	}
	
	public function update($data, $id) 
	{
		if (!$data || !$id || !is_array($data))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'gather_set SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	
	public function delete($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'gather_set WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
	public function audit($ids,$status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'gather_set SET is_open = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);
		return $arr;
	}
}
?>