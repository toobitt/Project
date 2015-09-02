<?php
/***************************************************************************
* $Id: company.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class company extends InitFrm
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
	 * 获取企业数据
	 * @param Array $data
	 */
	public function show($data)
	{
		if ($data['count'] != -1)
		{
			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		}
		$sql = 'SELECT c.*, m.host, m.dir, m.filepath, m.filename, t.name AS trade_name, g.name AS grade_name 
		FROM ' . DB_PREFIX . 'company c LEFT JOIN ' . DB_PREFIX . 'material m 
		ON c.logo = m.id LEFT JOIN ' . DB_PREFIX . 'trade t ON c.trade_id = t.id 
		LEFT JOIN ' . DB_PREFIX . 'grade g ON c.grade_id = g.id WHERE c.is_drop = 0';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取企业数据总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'company c WHERE is_drop = 0';
		if ($condition) $sql .= $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个数据
	 * @param String $table
	 * @param Array $data
	 */
	public function detail($table, $data)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . $table .' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
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
	
	public function get_one_company($id)
	{
		$sql = 'SELECT c.*, m.host, m.dir, m.filepath, m.filename, t.name AS trade_name, g.name AS grade_name 
		FROM ' . DB_PREFIX . 'company c LEFT JOIN ' . DB_PREFIX . 'material m 
		ON c.logo = m.id LEFT JOIN ' . DB_PREFIX . 'trade t ON c.trade_id = t.id 
		LEFT JOIN ' . DB_PREFIX . 'grade g ON c.grade_id = g.id WHERE c.is_drop = 0 AND c.id = ' . $id;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'company WHERE is_drop = 0';
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
		return $result['total'];
	}
	
	public function get_pic($data)
	{
		if (!$data || !is_array($data)) return false;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'material WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		$sql .= ' ORDER BY id DESC';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
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
				if (is_int($val) || is_float($val))
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
	 * 删除企业信息
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
	 * 获取查询条件
	 * @param Array $data
	 */
	private function get_condition($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keyword'])
		{
			$condition .= " AND c.name LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据行业查询信息
		if ($data['trade_id'] > 0)
		{
			//获取子类
			$sql = 'SELECT id FROM ' . DB_PREFIX . 'trade WHERE pid IN (' . $data['trade_id'] . ')';
			$q = $this->db->query($sql);
			$trade_id = array();
			while ($r = $this->db->fetch_array($q))
			{
				$trade_id[$r['id']] = $r['id'];
			}
			if ($trade_id)
			{
				$trade_id = implode(',', $trade_id);
			}
			else
			{
				$trade_id = 0;
			}
			$condition .= " AND c.trade_id IN (" . $trade_id . ")";
		}
		
		//根据等级查询信息
		if ($data['grade_id'])
		{
			if (is_int($data['grade_id']) && $data['grade_id'] > 0)
			{
				$condition .= " AND c.grade_id = " . $data['grade_id'];
			}
			elseif (is_string($data['grade_id']))
			{
				$condition .= " AND c.grade_id IN (" . $data['grade_id'] . ")";
			}
		}
		
		//根据ID获取数据
		if ($data['id'])
		{
			if (is_int($data['id']))
			{
				$condition .= " AND c.id = " . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= " AND c.id IN (" . $data['id'] . ")";
			}
		}
		
		//根据用户获取数据
		if ($data['uid'])
		{
			if (is_int($data['uid']))
			{
				$condition .= " AND c.user_id = " . $data['uid'];
			}
			elseif (is_string($data['uid']))
			{
				$condition .= " AND c.user_id IN (" . $data['uid'] . ")";
			}
		}
		
		//查询状态
		switch ($data['state'])
		{
			case 1:
				$condition .= "";  //所有状态
				break;
			case 2:
				$condition .= " AND c.state = 0";  //待审核
				break;
			case 3:
				$condition .= " AND c.state = 1";  //已审核
				break;
			case 4:
				$condition .= " AND c.state = 2";  //未通过
				break;
			default:
				break;
		}
		
		if ($data['start_time'])
		{
			$start_time = strtotime($data['start_time']);
			$condition .= " AND c.create_time >= " . $start_time;
		}
		
		if ($data['end_time'])
		{
			$end_time = strtotime($data['end_time']);
			$condition .= " AND c.create_time < " . $end_time;
		}
		
		//查询发布的时间
        if ($data['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d', TIMENOW+24*3600));
			switch ($data['date_search'])
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d', TIMENOW-24*3600));
					$condition .= " AND  c.create_time > '" . $yesterday . "' AND c.create_time < '" . $today . "'";
					break;
				case 3://今天的数据
					$condition .= " AND  c.create_time > '" . $today . "' AND c.create_time < '" . $tomorrow . "'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d', TIMENOW-2*24*3600));
					$condition .= " AND  c.create_time > '" . $last_threeday . "' AND c.create_time < '" . $tomorrow . "'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d', TIMENOW-6*24*3600));
					$condition .= " AND  c.create_time > '" . $last_sevenday . "' AND c.create_time < '" . $tomorrow . "'";
					break;
				default://所有时间段
					break;
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
			$sort .= 'c.id DESC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
}
?>