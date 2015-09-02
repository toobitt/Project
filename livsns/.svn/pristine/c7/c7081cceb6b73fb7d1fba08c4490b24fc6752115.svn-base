<?php
class weather_source extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d h:i:s',$row['create_time']);
			$k[$row['id']] = $row;
		}
		return $k;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'weather_source WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function detail($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source WHERE id = '.$id;
		$row = $this->db->query_first($sql);
		return $row;
	}
	public function create($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'weather_source set ';
		foreach($data as $filed=>$value)
		{
			$sql .= "{$filed} = '".$value."',";
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	public function update($data,$id)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'weather_source set ';
		foreach($data as $filed=>$value)
		{
			$sql .= "{$filed} = '".$value."',";
		}
		$sql = trim($sql, ',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		return $data;
	}
	public function delete($ids)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_source where id in('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		return $ids;
	}
	
}