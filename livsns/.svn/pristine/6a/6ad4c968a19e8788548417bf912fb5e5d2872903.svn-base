<?php
class Classaudit extends InitFrm
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'auditset WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
			$row['infor'] = unserialize($row['infor']) ? unserialize($row['infor']) : '';
			switch ($row['is_open'])
			{
				case 0: $row['open_status'] = '未开启';break;
				case 1: $row['open_status'] = '已开启';break;
			}
			$row['format_start_date'] = date('Y-m-d', $row['start_time']);
			$row['format_end_date'] = $row['end_time'] ? date('Y-m-d', $row['end_time']) : '无限';
			$data[$row['id']] = $row;
		}
		return $data;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(id) AS total FROM '.DB_PREFIX.'auditset WHERE 1 AND '.$condition;
		return $this->db->query_first($sql);
	}
	
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'auditset WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if (!empty($ret))
		{
			$ret['infor'] = unserialize($ret['infor']) ? unserialize($ret['infor']) : '';
			$ret['week_day'] = $ret['week_day'] ? explode(',', $ret['week_day']) : '';
		}
		return $ret;
	}
	
	public function create($data)
	{
		if (!$data || empty($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'auditset SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'auditset SET order_id = '.$insert_id.' WHERE id = '.$insert_id;
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		return $data;
	}
	
	public function update($data, $id)
	{
		if (!$id || empty($data))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'auditset SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	

	public function delete($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'auditset WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}

	public function audit($ids,$status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'auditset SET is_open = '.$status.' WHERE id IN ('.$ids.')';
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