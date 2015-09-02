<?php
class weather extends InitFrm
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['one'] = $this->showlimit('one') ? unserialize($row['one']) : array();
			$row['two'] = $this->showlimit('two') ? unserialize($row['two']) : array();
			$row['three'] = $this->showlimit('three') ? unserialize($row['three']) : array();
			$row['four'] = $this->showlimit('four') ? unserialize($row['four']) : array();
			$row['five'] = $this->showlimit('five') ? unserialize($row['five']) : array();
			$row['six'] = $this->showlimit('six') ? unserialize($row['six']) : array();
			$row['seven'] = $this->showlimit('seven') ? unserialize($row['seven']) : array();
			$k[$row['id']] = $row;
		}
		if (!empty($k))
		{
			foreach ($k as $key=>$val)
			{
				$k[$key]['city_name'] = $val['id']? $this->show_name($val['id']) : '';
				$k[$key]['one'] = $val['one'] ? $this->show_arr($val['one']) : array();
				$k[$key]['two'] = $val['two'] ? $this->show_arr($val['two']) : array();
				$k[$key]['three'] = $val['three'] ? $this->show_arr($val['three']) : array();
				$k[$key]['four'] = $val['four'] ? $this->show_arr($val['four']) : array();
				$k[$key]['five'] = $val['five'] ? $this->show_arr($val['five']) : array();
				$k[$key]['six'] = $val['six'] ? $this->show_arr($val['six']) : array();
				$k[$key]['seven'] = $val['seven'] ? $this->show_arr($val['seven']) : array();
			}
		}
		return $k;
	}
	private function show_name($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE  id = '.$id;
		$ret = $this->db->query_first($sql);
		return $ret['name'];
	}
	private function show_arr($arr)
	{
		if (!$arr || empty($arr))
		{
			return array();
		}
		$arr['img']  = $arr['img'] ? $this->show_img($arr['img']) : array();
		return $arr;
	}
	private function show_img($ids)
	{		
		if (!$ids)
		{
			return array();
		}
		$k = array();		
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{ 
			if ($row['is_update'])
			{
				$k[$row['id']] = unserialize(stripslashes($row['user_img']));
			}else {
				$k[$row['id']] = unserialize($row['system_img']);
			}
		}
		$arr_ids = explode(',', $ids);
		foreach ($arr_ids as $key=>$val)
		{
			$arr[] = $k[$val];
		}
		return $arr;
	}
	public function showlimit($k)
	{
		$return = true;
		$num = WEATHER_DAYS+1;
		switch ($k)
		{
			case 'one':$return = (1<$num) ?true:false;break;
			case 'two':$return = (2<$num) ?true:false;break;
			case 'three':$return = (3<$num) ?true:false;break;
			case 'four':$return = (4<$num) ?true:false;break;
			case 'five':$return = (5<$num) ?true:false;break;
			case 'six':$return = (6<$num) ?true:false;break;
			case 'seven':$return = (7<$num) ?true:false;break;
		}
		
		return $return;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'weather WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function detail($id)
	{
		if (!$id)
		{
			return false;
		}
		$dayk = array(
			0 => 'one',
			1 => 'two',
			2 => 'three',
			3 => 'four',
			4 => 'five',
			5 => 'six',
			6 => 'seven',
		);
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		for($i = 0; $i < 6;$i++)
		{
			$k = unserialize($res[$dayk[$i]]);
			$imgs[$dayk[$i]] = $k['img'];
			$tmp[] = $k;
		}
		if ($imgs)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id IN ('.implode(',', $imgs).')';
			$query = $this->db->query($sql);
			$img = array();
			while ($row = $this->db->fetch_array($query))
			{
				
				if ($row['is_update'])
				{
					$material = unserialize($row['user_img']);
				}
				else
				{
					$material = unserialize($row['system_img']);			
				}
				if (!$material)
				{
					$material = '';
				}
				$img[$row['id']] = $material;	
			}
		}
		$ret = array();
		foreach ($tmp AS $v)
		{
			$weather_ico = explode(',', $v['img']);
			foreach ($weather_ico AS $iid)
			{
				if (!$img[$iid])
				{
					continue;
				}
				$v['icon'][] = $img[$iid];
			}
			$ret[] = $v;
		}
		return $ret;
	}
	//获取所有字段
	public function getField()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE 1 ORDER BY id ASC';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			 $k[] = $row;
		} 
		return $k;
	}
	public function update($id)
	{
		if (!$id)
		{
			return false;
		}
		$data = $this->input;
		$dayk = array(
			0 => 'one',
			1 => 'two',
			2 => 'three',
			3 => 'four',
			4 => 'five',
			5 => 'six',
			6 => 'seven',
		);
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		$tmp = array();
		for($i = 0; $i < 7;$i++)
		{
			$k = unserialize($res[$dayk[$i]]);
			foreach ($k as $f=>$v)
			{		
				if ($f=='img')
				{
					$arr =array('');
					$data[$f.'_'.$i] = array_diff($data[$f.'_'.$i], $arr);
					$k[$f] =  isset($data[$f.'_'.$i]) ? implode(',', $data[$f.'_'.$i]):$v;
				}else {
					$k[$f] =  isset($data[$f.'_'.$i]) ? $data[$f.'_'.$i]:$v;
				}						
			}
			$tmp[$i] = $k;
		}
		$sql = 'UPDATE '.DB_PREFIX.'weather SET ';
		foreach ($tmp AS $key=>$val)
		{
			$sql .= $dayk[$key].'="'.addslashes(serialize($val)).'",';
		}
		$sql = rtrim($sql,',');
		$sql.= ' WHERE id='.$id;
		$this->db->query($sql);
		return true;
	}
	
public function update_pm25_data_admin($data)
	{
		$id=$data['id'];
		unset($data['id']);
		if (!$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'aqi_data  SET ';
		foreach ($data AS $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql.= ' WHERE id='.$id;
		$this->db->query($sql);
		return true;
	}
	
	public function show_realtime($id)
	{
		$city_id = $id;
		$w_date = date('Y-m-d',TIMENOW);
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_information WHERE w_date = "'.$w_date.'" AND id ='.$city_id.' AND w_time !=""';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[] = $row;
		}
		return $k;
	}
	public function delete($id)
	{
		if (!$id)
		{
			return false;
		}
		//删除weather
		$sql = 'DELETE FROM '.DB_PREFIX.'weather WHERE id IN ('.$id.')';
		$this->db->query($sql);
		//删除city
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_city WHERE id IN ('.$id.')';
		$this->db->query($sql);
		//删除city_source
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_city_source WHERE id IN ('.$id.')';
		$this->db->query($sql);
		//删除information
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_information WHERE id IN ('.$id.')';
		$this->db->query($sql);
		//删除user_buffer
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_user_buffer WHERE id IN ('.$id.')';
		$this->db->query($sql);
		//删除user_define
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_user_define WHERE city_id IN ('.$id.')';
		$this->db->query($sql);
		return $id;
		
	}
	public function config_detial($id)
	{
		
		if (!$id)
		{
			return false;
		}
		$k = array();
		//获取所有天气源
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_source';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query)) 
		{
			$k['source'][$row['id']] = $row['support_name'];
			$k['dict'][$row['id']] = unserialize($row['dict']);
			
		}
		//获取所有字段
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE city_id = 0 OR city_id = '.$id;
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$k['field'][$row['user_field']] = $row;
		}
		return $k;
		
		
	}
}