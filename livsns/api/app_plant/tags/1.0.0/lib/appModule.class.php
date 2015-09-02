<?php
/***************************************************************************
* $Id: appModule.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class appModule extends InitFrm
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
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'app_module WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		$solidify_id = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if (unserialize($rows['pic']))
			{
				$rows['pic'] = unserialize($rows['pic']);
			}
			if (unserialize($rows['press_pic']))
			{
				$rows['press_pic'] = unserialize($rows['press_pic']);
			}
			if ($rows['solidify_id'] > 0)
			{
			    $solidify_id[$rows['solidify_id']] = $rows['solidify_id'];
			}
			$info[] = $rows;
		}
		if ($solidify_id)
		{
		    $solidify_id = implode(',', $solidify_id);
		    $solidify_info = $this->getSolidifyInfo($solidify_id, $data['condition']['uid']);
		    if ($solidify_info)
		    {
		        foreach ($info as $k => $v)
		        {
		            if ($solidify_info[$v['solidify_id']])
		            {
		                $info[$k]['solidify'] = $solidify_info[$v['solidify_id']];
		            }
		        }
		    }
		}
		return $info;
	}
	
	private function getSolidifyInfo($solidify_id, $user_id)
	{
	    if ($user_id)
	    {
	        $sql = 'SELECT m.id, m.mark, m.name, m.pic, u.param FROM ' . DB_PREFIX . 'solidify_module m 
	        INNER JOIN ' . DB_PREFIX . 'solidify_user u ON m.id = u.solidify_id 
	        WHERE m.id IN (' . $solidify_id . ') AND u.user_id = ' . $user_id;
	    }
	    else
	    {
	        $sql = 'SELECT id, mark, name, pic FROM ' . DB_PREFIX . 'solidify_module WHERE id IN (' . $solidify_id . ')';
	    }
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        if ($rows['pic'] && unserialize($rows['pic']))
	        {
	            $rows['pic'] = unserialize($rows['pic']);
	        }
	        if ($rows['param'] && unserialize($rows['param']))
	        {
	            $rows['param'] = unserialize($rows['param']);
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_module WHERE 1';
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_module WHERE 1';
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
	 * 根据模块获取栏目数据
	 * @param integer $app_id
	 */
	public function getColumnsByModule($app_id)
	{
	    $sql = 'SELECT column_id FROM ' . DB_PREFIX . 'app_module 
	    WHERE web_view = 0 AND app_id = ' . $app_id . ' ORDER BY sort_order ASC';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $info[] = $rows['column_id'];
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
		
		//根据APP的id获取数据
		if (isset($data['app_id']))
		{
			if (is_numeric($data['app_id']))
			{
				$condition .= " AND app_id = " . $data['app_id'];
			}
			elseif (is_string($data['app_id']))
			{
				$condition .= " AND app_id IN (" . $data['app_id'] . ")";
			}
		}
		
		//根据用户获取数据
		if (isset($data['uid']))
		{
			if (is_numeric($data['uid']))
			{
				$condition .= " AND user_id = " . $data['uid'];
			}
			elseif (is_string($data['uid']))
			{
				$condition .= " AND user_id IN (" . $data['uid'] . ")";
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