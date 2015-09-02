<?php
class invitationCodeClass extends InitFrm
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
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'invitation_code WHERE 1';
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
	 * 获取数据总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'invitation_code WHERE 1';
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
			    if ($k == 'uuid')
			    {
			        $fields .= $k . '=uuid()' . ',';
			    }
			    else
			    {
			        $fields .= $k . "='" . addslashes($v) . "',";
			    }
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
				    if (strpos($val, ',') === false)
				    {
				        $sql .= ' AND ' . $key . ' = "' . $val . '"';
				    }
				    else
				    {
				        $sql .= ' AND ' . $key . ' in (' . $val . ')';
				    }
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
		
		//是否已发送
		if (isset($data['send_id']))
		{
		    if ($data['send_id'] == -1)
		    {
		        //未发送
		        $condition .= " AND send_to_id = 0";
		    }
		    elseif ($data['send_id'] == 1)
		    {
		        //已发送
		        $condition .= " AND send_to_id != 0";
		    }
		}
		
		//是否已使用
		if (isset($data['user_id']))
		{
		    if ($data['user_id'] == -1)
		    {
		        //未使用
		        $condition .= " AND user_id = 0";
		    }
		    elseif ($data['user_id'] == 1)
		    {
		        //已使用
		        $condition .= " AND user_id != 0";
		    }
		}
		
		if ($data['start_time'])
		{
			$start_time = strtotime($data['start_time']);
			$condition .= " AND create_time >= " . $start_time;
		}
		
		if ($data['end_time'])
		{
			$end_time = strtotime($data['end_time']);
			$condition .= " AND create_time < " . $end_time;
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
					$condition .= " AND create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d', TIMENOW-2*24*3600));
					$condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d', TIMENOW-6*24*3600));
					$condition .= " AND create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";
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
			$sort .= 'id DESC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
}
?>