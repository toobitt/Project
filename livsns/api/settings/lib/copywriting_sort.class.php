<?php
class copywriting_sort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset,$count,$field='*')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "copywriting_sort WHERE 1 ".$condition.$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if($row['value'])
			{
				$row['value']=html_entity_decode($row['value']);
			}
			if(!empty($row['icon']))
			{
				$row['icon']=unserialize($row['icon']);
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "copywriting_sort WHERE id = " . $id;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			if($row['value'])
			{
				$row['value']=html_entity_decode($row['value']);
			}
			if(!empty($row['icon']))
			{
				$row['icon']=unserialize($row['icon']);
			}
			return $row;
		}
		return false;
	}

	/**
	 *
	 * 开关 ...
	 */
	public function display($ids, $opened)
	{
		$sql = 'UPDATE '.DB_PREFIX.'copywriting_sort SET is_on = '.$opened.' WHERE id = '.$ids;
		$this->db->query($sql);
		$arr = array(
			'id'=>$ids,
			'opened'=>$opened,
		);
		return $arr;
	}
	
	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $order=false,$pk = 'id')
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
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table&&$order)//更新附加信息表排序
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
	public function update($table, $data, $idsArr, $flag = false)
	{
			
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($data as $k => $v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
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
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val)||is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($member_id, ',')!==false))
				{
					$sql .= ' AND ' . $key . ' in (\'' . $val . '\')';
				}
				elseif (is_array($var))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
				elseif(is_string($val))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}		
		$res=$this->db->query($sql);
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
		if($table&&$order)//更新附加信息表排序
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
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . $table .' WHERE 1';
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

?>