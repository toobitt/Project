<?php
/***************************************************************************
* $Id: appInterface.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class appInterface extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取APP数据
	 * @param Array $data
	 */
	public function show($data)
	{
		if ($data['count'] != -1)
		{
			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_interface WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if (unserialize($rows['pic']))
			{
				$rows['pic'] = unserialize($rows['pic']);
			}
			$info[$rows['id']] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取数据总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_interface WHERE 1';
		if ($condition) $sql .= $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个数据
	 * @param String $table
	 * @param Array $data
	 * @param String $fields
	 */
	public function detail($table, $data, $fields = '*')
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . $table .' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		return $this->db->query_first($sql);
	}
	
	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_interface WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
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
		return $result['total'];
	}
	
	/**
	 * 根据界面的id获取属性节点数据
	 * @param Int $ui_id
	 * @param Int $module_id
	 * @param Boolean $flag 是否关联查询
	 */
	public function get_attribute($ui_id, $module_id = 0, $flag = false)
	{
		if ($flag) $fields = 'ua.';
		if (intval($ui_id) > 0)
		{
			$condition = ' AND ' . $fields . 'ui_id = ' . $ui_id;
		}
		if ($flag)
		{
			
			$sql = 'SELECT a.*, ua.ui_id, ua.def_val AS defVal, ua.name AS new_name, ua.brief AS new_brief 
			FROM ' . DB_PREFIX . 'app_attribute a, ' . DB_PREFIX . 'ui_attr ua WHERE a.id = ua.attr_id' . $condition;
			if (intval($module_id) > 0)
			{
				$sql = 'SELECT attr.*, uv.attr_value, uv.module_id FROM (' . $sql . ') AS attr 
				LEFT JOIN ' . DB_PREFIX . 'ui_value uv ON attr.ui_id = uv.ui_id 
				AND attr.id = uv.attr_id AND uv.module_id = ' . $module_id;
			}
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'ui_attr WHERE 1' . $condition;
		}
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if ($flag)
			{
				$arr = $rows;
				$arr['name'] = $rows['new_name'] ? $rows['new_name'] : $rows['name'];
				$arr['brief'] = $rows['new_brief'] ? $rows['new_brief'] : $rows['brief'];
				$arr['def_val'] = $rows['defVal'] ? $rows['defVal'] : $rows['def_val'];
				if (unserialize($arr['def_val']))
				{
					$arr['def_val'] = unserialize($arr['def_val']);
				}
				unset($arr['new_name']);
				unset($arr['new_brief']);
				unset($arr['defVal']);
				$info[$rows['ui_id']][] = $arr;
			}
			else
			{
				if (unserialize($rows['def_val']))
				{
					$rows['def_val'] = unserialize($rows['def_val']);
				}
				$info[] = $rows;
			}
		}
		return $info;
	}
	
	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $pk = 'id')
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
			elseif (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$data[$pk] = $this->db->insert_id();
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
				if (is_numeric($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
				elseif (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
				}
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除信息
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
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
	 * 获取查询条件
	 * @param Array $data
	 */
	private function get_condition($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keyword'])
		{
			$condition .= " AND name LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据模板id获取数据
		if (isset($data['temp_id']))
		{
			if (is_numeric($data['temp_id']))
			{
				$condition .= " AND temp_id = " . $data['temp_id'];
			}
			elseif (is_string($data['temp_id']))
			{
				$condition .= " AND temp_id IN (" . $data['temp_id'] . ")";
			}
		}
		
		//根据ID获取数据
		if (isset($data['id']))
		{
			if (is_numeric($data['id']))
			{
				$condition .= " AND id = " . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= " AND id IN (" . $data['id'] . ")";
			}
		}
		
		//排序
		$sort = ' ORDER BY ';
		if ($data['order'] && is_array($data['order']))
		{
			foreach ($data['order'] as $k => $v)
			{
				$sort .= $k . ' ' . $v . ', ';
			}
			$sort = rtrim($sort, ', ');
		}
		else
		{
			$sort .= 'sort_order ASC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
}
?>