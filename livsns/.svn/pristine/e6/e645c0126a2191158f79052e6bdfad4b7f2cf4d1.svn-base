<?php
/***************************************************************************
* $Id: appFeedback.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class appFeedback extends InitFrm
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
		$sql = 'SELECT f.*, a.name AS app_name, c.name AS client_name FROM ' . DB_PREFIX . 'app_feedback f 
		LEFT JOIN ' . DB_PREFIX . 'app_info a ON f.app_id = a.id LEFT JOIN '.DB_PREFIX.'app_client c 
		ON f.client_type = c.id WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$ids = $info = array();
		while ($rows = $this->db->fetch_array($query))
		{
		    $ids[] = $rows['id'];
		    if (html_entity_decode($rows['content']))
		    {
		        $rows['content'] = html_entity_decode($rows['content']);
		    }
			$info[] = $rows;
		}
		if ($data['flag'] && $ids)
		{
		    $reply_id = implode(',', $ids);
		    $sql = 'SELECT * FROM ' . DB_PREFIX . 'app_reply WHERE reply_id IN (' . $reply_id . ') ORDER BY reply_time ASC';
		    $q = $this->db->query($sql);
		    $reply_info = array();
		    while ($row = $this->db->fetch_array($q))
		    {
		        if (html_entity_decode($row['reply_content']))
		        {
		            $row['reply_content'] = html_entity_decode($row['reply_content']);
		        }
		        $reply_info[$row['reply_id']][] = $row;
		    }
		    if ($reply_info)
		    {
		        foreach ($info as $k => $v)
		        {
		            if ($reply_info[$v['id']]) $info[$k]['reply'] = $reply_info[$v['id']];
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
		$sql = 'SELECT COUNT(f.app_id) AS total FROM ' . DB_PREFIX . 'app_feedback f WHERE 1';
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
			$condition .= " AND f.content LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据APP_ID获取数据
		if (isset($data['app_id']))
		{
			if (is_numeric($data['app_id']))
			{
				$condition .= " AND f.app_id = " . $data['app_id'];
			}
			elseif (is_string($data['app_id']))
			{
				$condition .= " AND f.app_id IN (" . $data['app_id'] . ")";
			}
		}
		
		//根据客户端类型获取数据
		if (isset($data['client_type']))
		{
		    if (is_numeric($data['client_type']))
			{
				$condition .= " AND f.client_type = " . $data['client_type'];
			}
			elseif (is_string($data['client_type']))
			{
				$condition .= " AND f.client_type IN (" . $data['client_type'] . ")";
			}
		}
		
		//根据设备序列号获取数据
		if ($data['device_token'])
		{
		    $condition .= " AND f.device_token = '" . $data['device_token'] . "'";
		}
		
		if ($data['start_time'])
		{
			$start_time = strtotime($data['start_time']);
			$condition .= " AND f.create_time >= " . $start_time;
		}
		
		if ($data['end_time'])
		{
			$end_time = strtotime($data['end_time']);
			$condition .= " AND f.create_time < " . $end_time;
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
					$condition .= " AND f.create_time > '" . $yesterday . "' AND f.create_time < '" . $today . "'";
					break;
				case 3://今天的数据
					$condition .= " AND f.create_time > '" . $today . "' AND f.create_time < '" . $tomorrow . "'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d', TIMENOW-2*24*3600));
					$condition .= " AND f.create_time > '" . $last_threeday . "' AND f.create_time < '" . $tomorrow . "'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d', TIMENOW-6*24*3600));
					$condition .= " AND f.create_time > '" . $last_sevenday . "' AND f.create_time < '" . $tomorrow . "'";
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
			$sort .= 'f.id ASC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
	
	/**
	 * 得到所有的设备的回复信息
	 * @param unknown $data
	 * @return multitype:unknown string
	 */
	public function getFeedBackDevices($data)
	{
		$info = array();
		$appId = $data['condition']['app_id'];
		$getDeviceSql = 'select * from (SELECT * FROM '.DB_PREFIX.'app_feedback WHERE app_id = '.$appId.' order by create_time  desc) as temp group by device_token order by create_time desc';
		$query = $this->db->query($getDeviceSql);
		while ($rows = $this->db->fetch_array($query))
		{
			if (html_entity_decode($rows['content']))
			{
				$rows['content'] = html_entity_decode($rows['content']);
			}
			$info[] = $rows;
		}
		foreach($info as $k => &$v)
		{
			if($v['client_type']==2)
			{
				$v['client_type'] = 'iOS';
			}
			else if($v['client_type']==1)
			{
				$v['client_type'] = 'Android';
			}
		}
		return $info;
	}
	
	/**
	 * 根据DEVICETOKEN ID得到具体信息
	 */
	public function getOneDeviceInfoById($app_id,$device_token)
	{
		//先得到这个device的所有意见 根据时间升序
		$sql = "select * from ".DB_PREFIX."app_feedback where 1 and app_id = " . $app_id . " and device_token = '" .$device_token ."'  order by create_time asc";
		$query = $this->db->query($sql);
		$ids = $info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$ids[] = $rows['id'];
			if (html_entity_decode($rows['content']))
			{
				$rows['content'] = html_entity_decode($rows['content']);
			}
			$info[] = $rows;
		}
		$reply_id = implode(',', $ids);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_reply WHERE reply_id IN (' . $reply_id . ') ORDER BY reply_time ASC';
		$q = $this->db->query($sql);
		$reply_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			if (html_entity_decode($row['reply_content']))
			{
				$row['reply_content'] = html_entity_decode($row['reply_content']);
			}
			$reply_info[$row['reply_id']][] = $row;
		}
		if ($reply_info)
		{
			foreach ($info as $k => $v)
			{
				if ($reply_info[$v['id']]) $info[$k]['reply'] = $reply_info[$v['id']];
			}
		}
		return $info;
	}
	/**
	 * 删除所有反馈意见信息
	 * @param unknown $table
	 * @param unknown $data
	 * @return boolean
	 */
	public function deleteFeedbackByDeviceTokenAndAppid($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			else
			{
				$sql .= " AND " . $k . " = '" . $v ."'";
			}
		}
		return $this->db->query($sql);
	}
	
	public function getReplysByDeviceTokenAndAppid($app_id,$device_token)
	{
		$sql = "select * from ".DB_PREFIX."app_feedback where app_id = ".$app_id ." and device_token = '".$device_token."'";
		$info = array();
		$query = $this->db->query($sql);
		while ($rows = $this->db->fetch_array($query))
		{
			$ids[] = $rows['id'];
			if (html_entity_decode($rows['content']))
			{
				$rows['content'] = html_entity_decode($rows['content']);
			}
			$info[] = $rows;
		}
		return $info;
	}
}
?>