<?php/**************************************************************************** $Id: user.class.php 17481 2013-04-19 09:36:46Z yaojian $***************************************************************************/class user extends InitFrm{	public function __construct()	{		parent::__construct();	}	public function __destruct()	{		parent::__destruct();	}		/**	 * 获取数据	 * @param Array $data	 */	public function show($data)	{		if ($data['count'] != -1)		{			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];		}		$sql = 'SELECT * FROM ' . DB_PREFIX . 'user WHERE 1';		//获取查询条件		$condition = $this->get_condition($data['condition']);		$sql .= $condition;		if ($data_limit) $sql .= $data_limit;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			if (unserialize($rows['avatar']))			{				$rows['avatar'] = unserialize($rows['avatar']);			}			$info[] = $rows;		}		return $info;	}		/**	 * 获取总数	 * @param String $table	 * @param Array $data	 */	public function count($table, $data)	{		$condition = $this->get_condition($data);		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . $table . ' WHERE 1';		if ($condition) $sql .= $condition;		return $this->db->query_first($sql);	}		/**	 * 获取单个数据	 * @param String $table	 * @param Array $data	 */	public function detail($table, $data)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . $table .' WHERE 1';		foreach ($data as $k => $v)		{			if (is_int($v) || is_float($v))			{				$sql .= ' AND ' . $k . ' = ' . $v;			}			elseif (is_string($v))			{				$sql .= ' AND ' . $k . ' = "' . $v . '"';			}		}		return $this->db->query_first($sql);	}		/**	 * 创建数据	 * @param String $table 表	 * @param Array $data 数据	 * @param String $pk 主键	 */	public function create($table, $data, $pk = 'id')	{		if (!$table || !is_array($data)) return false;		$fields = '';		foreach ($data as $k => $v)		{			if (is_string($v))			{				$fields .= $k . "='" . $v . "',";			}			elseif (is_int($v) || is_float($v))			{				$fields .= $k . '=' . $v . ',';			}		}		$fields = rtrim($fields, ',');		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;		$this->db->query($sql);		$data[$pk] = $this->db->insert_id();		return $data;	}		/**	 * 更新数据	 * @param String $table 表	 * @param Array $data 数据	 * @param Array $idsArr 条件	 * @param Boolean $flag	 */	public function update($table, $data, $idsArr, $flag = false)	{		if (!$table || !is_array($data) || !is_array($idsArr)) return false;		$fields = '';		foreach ($data as $k => $v)		{			if ($flag)			{				$v = $v > 0 ? '+' . $v : $v;				$fields .= $k . '=' . $k . $v . ',';			}			else			{				if (is_string($v))				{					$fields .= $k . "='" . $v . "',";				}				elseif (is_int($v) || is_float($v))				{					$fields .= $k . '=' . $v . ',';				}			}		}		$fields = rtrim($fields, ',');		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';		if ($idsArr)		{			foreach ($idsArr as $key => $val)			{				if (is_int($val) || is_float($val))				{					$sql .= ' AND ' . $key . ' = ' . $val;				}				elseif (is_string($val))				{					$sql .= ' AND ' . $key . ' in (' . $val . ')';				}			}		}		return $this->db->query($sql);	}		/**	 * 删除数据	 * @paramString $table	 * @param Array $data	 */	public function delete($table, $data)	{		if (empty($table) || !is_array($data)) return false;		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';		foreach ($data as $k => $v)		{			if (is_int($v) || is_float($v))			{				$sql .= ' AND ' . $k . ' = ' . $v;			}			elseif (is_string($v))			{				$sql .= ' AND ' . $k . ' IN (' . $v . ')';			}		}		return $this->db->query($sql);	}		/**	 * 获取查询条件	 * @param Array $data	 */	private function get_condition($data)	{		$condition = '';				//根据企业ID获取数据		if ($data['cid'])		{			if (is_int($data['cid']))			{				$condition .= " AND c_id = " . $data['cid'];			}			elseif (is_string($data['cid']))			{				$condition .= " AND c_id IN (" . $data['cid'] . ")";			}		}				//根据ID获取数据		if ($data['id'])		{			if (is_int($data['id']))			{				$condition .= " AND id = " . $data['id'];			}			elseif (is_string($data['id']))			{				$condition .= " AND id IN (" . $data['id'] . ")";			}		}				if($data['dingdone_role_id'])		{			$condition .= " AND dingdone_role_id = " . $data['dingdone_role_id'];		}				if($data['push_status'])		{			$condition .= " AND push_status = " . $data['push_status'];		}				if ($data['start_time'])		{			$start_time = strtotime($data['start_time']);			$condition .= " AND create_time >= " . $start_time;		}				if ($data['end_time'])		{			$end_time = strtotime($data['end_time']);			$condition .= " AND create_time < " . $end_time;		}				if ($data['start_date'])		{			$condition .= " AND create_time >= " . $data['start_date'];		}				if ($data['end_date'])		{			$end_time = strtotime($data['end_time']);			$condition .= " AND create_time < " . $data['end_date'];		}				//查询发布的时间        if ($data['date_search'])		{			$today = strtotime(date('Y-m-d'));			$tomorrow = strtotime(date('Y-m-d', TIMENOW+24*3600));			switch ($data['date_search'])			{				case 1://所有时间段					break;				case 2://昨天的数据					$yesterday = strtotime(date('y-m-d', TIMENOW-24*3600));					$condition .= " AND create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";					break;				case 3://今天的数据					$condition .= " AND create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";					break;				case 4://最近3天					$last_threeday = strtotime(date('y-m-d', TIMENOW-2*24*3600));					$condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";					break;				case 5://最近7天					$last_sevenday = strtotime(date('y-m-d', TIMENOW-6*24*3600));					$condition .= " AND create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";					break;				default://所有时间段					break;			}		}				//排序		$sort = ' ORDER BY ';		if ($data['order'] && is_array($data['order']))		{			foreach ($data['order'] as $k => $v)			{				$sort .= $k . ' ' . $v . ', ';			}			$sort = rtrim($sort, ', ');		}		else		{			$sort .= 'id DESC';		}		$condition = $condition . $sort;		return $condition;	}		public function getNewLoginRecord($user_id = 0)	{		$sql = "select * from ".DB_PREFIX."user_login_time where user_id =".$user_id." order by id desc";		$info = $this->db->fetch_all($sql);		return $info;	}		public function getRecordTimes($login_time = 0 , $user_id = 0)	{		$sql = "select * from ".DB_PREFIX."user_login_time where user_id =".$user_id." and login_time>".$login_time;		$info = $this->db->fetch_all($sql);		return $info;	}		public function other_count($table ='' , $condition = '')	{		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . $table . ' WHERE 1';		$sql .= $condition;		return $this->db->query_first($sql);	}		public function getLiushi($start_time = 0 , $end_time = 0)	{		$sql = "select * from ".DB_PREFIX."user_login_time where login_time>".$start_time." and login_time<".$end_time;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			if (unserialize($rows['avatar']))			{				$rows['avatar'] = unserialize($rows['avatar']);			}			$info[] = $rows;		}		return $info;	}		public function getTodayAddInfo($time = 0)	{		$sql = "select count(*) as total,t as hour from (SELECT id,user_name, FROM_UNIXTIME(create_time,'%H') as t,create_time FROM ".DB_PREFIX."user where create_time > ".$time." ORDER BY `id` DESC) as new_table  group by new_table.t";		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		public function getTodayActivateInfo($time = 0)	{		$sql = "select count(*) as total,t as hour from (SELECT id,user_name, FROM_UNIXTIME(login_time,'%H') as t,login_time FROM ".DB_PREFIX."user_login_time where login_time > ".$time." ORDER BY `id` DESC) as new_table  group by new_table.t";		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}				return $info;		}		public function getTodayPushInfo()	{		$sql = "select count(*) as total,t as hour from (SELECT id,user_name, FROM_UNIXTIME(login_time,'%H') as t,login_time FROM ".DB_PREFIX."user_login_time where login_time > ".$time." ORDER BY `id` DESC) as new_table  group by new_table.t";		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		public function getActivateInfoIndate($start_time = 0,$end_time = 0)	{		$sql = "select count(*) as total from ".DB_PREFIX."user_login_time where login_time >".$start_time." and login_time <".$end_time;		$info = $this->db->query_first($sql);		return $info;	}		public function getTodayAddDevelopInfo($start_time = 0,$end_time = 0)	{		$sql = "select count(*) as total,t as hour from (SELECT FROM_UNIXTIME(is_developer_time,'%H') as t FROM ".DB_PREFIX."user where is_developer_time > ".$start_time." and is_developer_time < ".$end_time." ) as new_table  group by new_table.t";		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		$ret = array();		if($info && is_array($info))		{			foreach ($info as $k => $v)			{				$ret[intval($v['hour'])] =  intval($v['total']);			}		}		return $ret;	}}?>