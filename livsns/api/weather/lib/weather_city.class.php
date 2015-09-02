<?php
class weather_city extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition, $orderby=' ORDER BY id DESC', $offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 ".$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k =array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$row['create_time'] = date('Y-m-d h:i:s',$row['create_time']);
			$row['update_time'] = date('Y-m-d h:i:s',$row['update_time']);
			if (!empty($row['input_sort']))
			{
				$row['sortname'] = $this->get_sortName($row['input_sort']);
			}		
			$row['input_sort'] = explode(',', $row['input_sort']);
			switch ($row['is_auto'])
			{
				case  1: $row['auto'] = '开启';break;
				default: $row['auto'] = '';
			}
			$k[] = $row;
		}
		return $k;	
	}
	public function fastInput_sort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort';
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ( $row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row['name'];
		}
		return $k;
	}
	public function get_sortName($ids)
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'fastInput_sort WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row['name'];
		} 
		return $k;
	}
}