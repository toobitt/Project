<?php
class Csql extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function where($idsArr)
	{
		$where = '';
		if (is_array($idsArr))
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val)||is_numeric($val))
				{
					$where .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')!==false))
				{
					$where .= ' AND ' . $key . ' in (' . $val . ')';
				}
				elseif (is_array($val))
				{
					$where .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
				elseif(is_string($val))
				{
					$where .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}
		elseif (is_string($idsArr))
		{
			$where = $idsArr;
		}
		return $where;
	}
	public function detail($id,$table,$format = array(),$field='*')
	{
		if(is_array($id))
		{
			$condition = $this->where($id);
		}
		elseif($id)
		{
			$condition = 'AND id = \''.$id.'\'';
		}
		else 
		{
			return array();
		}
		$sql = "SELECT {$field} FROM " . DB_PREFIX . $table .' WHERE 1 '.$condition;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			return $this->dataFormat($format, $row);
		}
		return array();
	}
	public function limit($offset,$count)
	{
		return $count?$limit 	 = " LIMIT " . (int)$offset . " , " . (int)$count:'';
	}
	public function orderby($orderby)
	{
		return $orderby?' '.$orderby:'';
	}
	
	/**
	 * 
	 *  join处理...
	 * @param unknown_type $join
	 */
	public function join($join)
	{
		return $join;
	}
	
	public function show($idArr,$table,$offset = 0,$count = 0,$orderby = '',$field = '*',$key = '',$format = array(),$type = 1,$otherKey = '',$join = '')
	{
		$ret = array();
		$row = array();
		$sql = 'SELECT '.$field.' FROM '.DB_PREFIX.$table.' t '.$this->join($join).' WHERE 1 '.$this->where($idArr).$this->orderby($orderby).$this->limit($offset, $count);
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$row = $this->dataFormat($format, $row);	
			if($key&&$type==1){
				$ret[$row[$key]] = $row;
			}
			elseif ($key&&$type==2)
			{
				$ret[$row[$key]][] = $row;
			}
			elseif ($key&&$type==3)
			{
				$ret[$row[$key]][] = $row[$otherKey];
			}
			elseif($key&&!$type)
			{
				$ret[] = $row[$key];
			}
			else {
				$ret[] = $row;
			}
		}
		return $ret;
	}

	public function dataFormat($format,$row)
	{
		if(is_array($format)&&$format)
		foreach ($format as $k => $v)
		{
			if(isset($row[$k]))
			{
				if($v['type'] == 'date')
				{
					
					$row[$k] = $row[$k]?date($v['format'],$row[$k]):'';
				}elseif ($v['type'] == 'array')
				{
					$row[$k] = $row[$k]?call_user_func('maybe_'.$v['format'],$row[$k]):array();
				}
				elseif ($v['type'] == 'explode')
				{
					$row[$k] = $row[$k]?explode($v['delimiter'], $row[$k]):array();
				}
			}
		}
		return $row;
	}

	public function count($idsArr,$table)
	{
		if(is_array($idsArr))
		{
			$condition = $this->where($idsArr);
		}
		elseif (is_string($idsArr))
		{
			$condition = $idsArr;
		}
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . $table .' WHERE 1 '.$condition;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			return (int)$row['total'];
		}
		return 0;
	}
	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $order=false,$pk = 'id',$replace=false)
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		$cmd = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
		$sql = $cmd.DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table&&$order)
		{
			$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
			$this->db->query($sql);
		}
		$data[$pk] = $id;
		return $data;
	}

	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr=array(), $flag = false,$math_key = array())
	{
			
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($data as $k => $v)
		{
			if ($flag&&(empty($math_key)||array_key_exists($k, $math_key)))
			{
				$math = empty($math_key)?'+':$math_key[$k];
				$v = $v >= 0 ? $math . $v : $v;
				$fields .= $k . '=' . $k . $v . ',';
			}
			else
			{
				if (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
				elseif (is_int($v) || is_float($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1'.$this->where($idsArr);
		$res = $this->db->query($sql);
		if ($idsArr&&$res)
		{
			return $idsArr;
		}
		return false;

	}

	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function replace($table, $data, $order=false,$pk = 'id')
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'REPLACE INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table&&$order)
		{
			$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
			$this->db->query($sql);
		}
		$data[$pk] = $id;
		return $data;
	}

	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $key => $val)
		{
			if (is_int($val) || is_float($val)||is_numeric($val))
			{
				$sql .= ' AND ' . $key . ' = ' . $val;
			}
			elseif (is_string($val)&&(stripos($val, ',')!==false))
			{
				$sql .= ' AND ' . $key . ' in (' . $val . ')';
			}
			elseif (is_array($val))
			{
				$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
			}
			elseif(is_string($val))
			{
				$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
			}
		}
		return $this->db->query($sql);
	}

	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($table,$data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . $table .' WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$condition .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$condition .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result['total']?true:false;
	}

}
