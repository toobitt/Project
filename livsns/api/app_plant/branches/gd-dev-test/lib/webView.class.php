<?php
/***************************************************************************
* $Id: webView.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class webView extends InitFrm
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
		$fields = $data['fields'] ? $data['fields'] : '*';
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'app_webview WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
		    $rows['create_time'] = date('Y-m-d H:i',$rows['create_time']);
			$info[] = $rows;
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_webview WHERE 1';
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_webview WHERE 1';
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
		
		//根据ID获取数据
		if ($data['id'])
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
			$sort .= 'id DESC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
}
?>