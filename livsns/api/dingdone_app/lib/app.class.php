<?php
/***************************************************************************
* $Id: app.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class app extends InitFrm
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
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'app_info WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = $app_ids = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if (unserialize($rows['icon']))
			{
				$rows['icon'] = unserialize($rows['icon']);
			}
			if (unserialize($rows['startup_pic']))
			{
				$rows['startup_pic'] = unserialize($rows['startup_pic']);
			}
			$app_ids[] = $rows['id'];
			$info[] = $rows;
		}
		if ($app_ids)
		{
			$app_client = $this->get_client(implode(',', $app_ids), true);
			if ($app_client)
			{
				foreach ($info as $k => $v)
				{
				    if ($app_client[$v['id']])
				    {
				        $info[$k]['client'] = $app_client[$v['id']];
				    }
				}
			}
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_info WHERE 1';
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
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_info WHERE 1';
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
	 * 获取APP对应的打包程序数据
	 * @param Int|String $app_id
	 */
	public function get_client($app_id, $flag = false, $var = false)
	{
		if (is_numeric($app_id))
		{
			$condition = ' AND cr.app_id = ' . $app_id;
		}
		elseif (is_string($app_id))
		{
			$condition = ' AND cr.app_id IN (' . $app_id . ')';
		}
		$sql = 'SELECT cr.*, c.name, c.mark, c.url FROM ' . DB_PREFIX . 'client_relation cr, ' . DB_PREFIX . 'app_client c 
		WHERE cr.client_id = c.id';
		if ($var) $sql .= ' AND cr.flag = 1';
		if ($condition) $sql .= $condition;
		$sql .= ' ORDER BY cr.client_id ASC';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if ($flag)
			{
				$info[$rows['app_id']][] = $rows;
			}
			else
			{
				$info[] = $rows;
			}
		}
		return $info;
	}
	
	public function client()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_client';
		$q = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($q))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	public function lastVersion($app_id, $client_id)
	{
	    $sql = 'SELECT * FROM ' . DB_PREFIX . 'publish_log WHERE app_id = ' . $app_id . ' 
	    AND client_id = ' . $client_id . ' ORDER BY publish_time DESC LIMIT 1';
	    return $this->db->query_first($sql);
	}
	
	/**
	 * 获取APP引导图
	 * @param Array $data
	 */
	public function app_pic($data)
	{
	    $sql = 'SELECT * FROM ' . DB_PREFIX . 'app_pic WHERE 1';
	    if ($data && is_array($data))
	    {
	        $condition = '';
	        foreach ($data as $k => $v)
	        {
    	        if (is_numeric($v))
    			{
    				$condition .= ' AND ' . $k . ' = ' . $v;
    			}
    			elseif (is_string($v))
    			{
    			    if (strpos($v, ','))
    			    {
    			        $condition .= ' AND ' . $k . ' IN (' . $v . ')';
    			    }
    			    else
    			    {
    			        $condition .= ' AND ' . $k . ' = "' . $v . '"';
    			    }
    			}
	        }
	        $sql .= $condition;
	    }
	    $sql .= ' ORDER BY sort_order ASC';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        if (unserialize($rows['info']))
	        {
	            $rows['info'] = unserialize($rows['info']);
	        }
	        $info[$rows['id']] = $rows;
	    }
	    return $info;
	}
	
	public function app_pic_count($data)
	{
	    $sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_pic WHERE 1';
	    if ($data && is_array($data))
	    {
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
	 * 删除APP对应的意见反馈
	 */
	public function deleteFeedback($app_id)
	{
	    $sql = 'SELECT id FROM ' . DB_PREFIX . 'app_feedback WHERE app_id = ' . $app_id;
	    $query = $this->db->query($sql);
	    $feedback_ids = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $feedback_ids[] = $rows['id'];
	    }
	    if ($feedback_ids)
	    {
	        $feedback_ids = implode(',', $feedback_ids);
	        $sql = 'DELETE FROM ' . DB_PREFIX . 'app_reply WHERE reply_id IN (' . $feedback_ids . ')';
	        $this->db->query($sql);
	    }
	    $this->delete('app_feedback', array('app_id' => $app_id));
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
		
		//删除状态
		if (isset($data['del']))
		{
		    $condition .= " AND del = " . $data['del'];
		}
		
		//根据客户端id获取app
		if (isset($data['client_id']))
		{
			$sql = 'SELECT app_id FROM ' . DB_PREFIX . 'client_relation WHERE client_id = ' . $data['client_id'];
			$q = $this->db->query($sql);
			$app_ids = array();
			while ($rows = $this->db->fetch_array($q))
			{
				$app_ids[$rows['app_id']] = $rows['app_id'];
			}
			$ids = $app_ids ? implode(',', $app_ids) : 0;
			$condition .= " AND id IN (" . $ids . ")";
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