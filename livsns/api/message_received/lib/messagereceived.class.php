<?phpclass messagereceivedClass extends InitFrm{	public function __construct()	{		parent::__construct();	}		public function __destruct()	{		parent::__destruct();	}		public function show($offset, $count, $data ,$cateid)	{		if ($count != -1)		{			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		}		$sql = 'SELECT * FROM ' . DB_PREFIX . 'sms WHERE active = 1 ';				if ($data && is_array($data))		{			$condition = $this->get_condition($data);		}		elseif ($data && is_string($data))		{			$condition = $data;		}		if ($condition) $sql .= $condition;		if ($cateid) $sql .= ' and cateid = '.$cateid;		$sql .= ' ORDER BY id DESC';		if ($data_limit) $sql .= $data_limit;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		//根据关键字查询总数	public function count($data)	{		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'sms WHERE active = 1';		$condition = $this->get_condition($data);		$sql .= $condition;		return $this->db->query_first($sql);	}	//根据号码查询是否存在	public function exists($kw,$data)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'sms as a,' . DB_PREFIX . 'sms_files as b WHERE a.id=b.sid and a.active = 1 and b.active = 1 ';		$sql .= " AND " . $kw . " = " . $data . " ";		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		//信息基本信息读取 $id	public function detail($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'sms WHERE active = 1 and id = ' . $id;		return $this->db->query_first($sql);	}		//信息附件读取 $id	public function detailfiles($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'sms_files WHERE active = 1 and sid = ' . $id;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		//基本信息添加操作	public function create($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'sms SET ' . implode(',', $fields);		$this->db->query($sql);				$data['id'] = $this->db->insert_id();		if ($data['id'])		{			return $data;		}		return false;	}		//图片，视频，附件添加操作	public function createfiles($data,$id)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'sms_files SET ' . implode(',', $fields);		if($sql) return $this->db->query($sql);	}		//更新基本信息	public function update($data, $id)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'sms SET ' . implode(',', $fields) . ' WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		$this->db->query($sql);		return $data;	}	//图片，视频，附件的更新操作	public function updatefiles($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}				$sql = 'INSERT INTO ' . DB_PREFIX . 'sms_files SET ' . implode(',', $fields);		$this->db->query($sql);		return $data;	}		public function delete($id)	{		$sql = 'UPDATE ' . DB_PREFIX . 'sms SET active = 0 WHERE 1';		$sqltemp = 'UPDATE ' . DB_PREFIX . 'sms_files SET active = 0 WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;			$sqltemp .= ' AND sid = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';			$sqltemp .= ' AND sid in (' . $id . ')';		}				$this->db->query($sqltemp);		return $this->db->query($sql);	}	public function audit($id,$status)	{		$status = intval($status);		$sql = 'update '.DB_PREFIX.'sms set status = '.$status.' where 1 ';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		if ($sql) return $this->db->query($sql);	}		public function emptyData($table)	{		$sql = 'TRUNCATE ' . DB_PREFIX . $table;		return $this->db->query($sql);	}		public function get_condition($data)	{		$condition = '';				//查询的关键字		if(isset($data['key']))		{			$keyword = htmlspecialchars(trim(urldecode($data['key'])));			$condition .= " AND title LIKE '%" . $keyword . "%' ";		}		return $condition;	}}?>