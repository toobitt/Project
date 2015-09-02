<?php
class gather_plan extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function check_plan()
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'gather_plan WHERE 1 limit 1';
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function delete_fail_data($num = 5)
	{
		if (!$num)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'gather_plan WHERE fail_count >='.$num;
		$this->db->query($sql);
		return true;
	}
	public function show($condition, $orderby, $offset, $count)
	{
		
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather_plan WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($query))
		{ 	
			$data[$row['id']] = $row;
		}
		return $data;		
	}
	
	public function update_set_url($setId, $id)
	{
		if (!$id || !is_array($setId))
		{
			return false;
		}
		$arr  = array();
		$sql = 'SELECT set_url FROM '.DB_PREFIX.'gather WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			$pre_set_url = unserialize($ret['set_url']);
			$arr = $pre_set_url;
		}
		foreach ($setId as $key=>$val)
		{
			$arr[$key] = $val;
		}
		if (empty($arr))
		{
			return false;
		}
		$arr = serialize($arr);
		$sql = 'UPDATE '.DB_PREFIX.'gather SET set_url ="'.addslashes($arr).'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	
	public function delete_plan($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'gather_plan WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
}