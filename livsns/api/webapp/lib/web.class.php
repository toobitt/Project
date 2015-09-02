<?phpclass webClass extends InitFrm{	public function __construct()	{		parent::__construct();	}		public function __destruct()	{		parent::__destruct();	}		public function show($offset, $count, $data)	{		if ($count != -1)		{			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		}		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp WHERE active = 1';		if ($data && is_array($data))		{			$condition = $this->get_condition($data);		}		elseif ($data && is_string($data))		{			$condition = $data;		}		if ($condition) $sql .= $condition;				$sql .= ' ORDER BY order_id asc,id DESC';		if ($data_limit) $sql .= $data_limit;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$rows['updown'] = unserialize($rows['updown']);			$rows['mood'] = unserialize($rows['mood']);			$info[] = $rows;		}		return $info;	}		//排行配置输出	public function show_rank()	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp_rank WHERE active = 1 ORDER BY order_id asc,id DESC ';				$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		//分类配置输出	public function show_pic_setting($type)	{		if($type=="1")		{			$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp_material WHERE active = 1 and type = 1 and is_on = 1 ORDER BY type asc,id DESC ';		}		elseif($type=="4")		{			$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp_material WHERE active = 1 and type = 4 and is_on = 1 ORDER BY type asc,id DESC ';		}		else {			$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp_material WHERE active = 1 and type in ("2","3")   and is_on = 1 ORDER BY type asc,id DESC ';		}				$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$rows['user_img'] = $rows['user_img']?unserialize($rows['user_img']):array();			$rows['app_user_image'] = $rows['app_user_image']?unserialize($rows['app_user_image']):array();			$info[] = $rows;		}		return $info;	}		//单条内容的详细输出	public function show_news_detail($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp WHERE active = 1 and content_id = ' . $id;		$result = array();		$result = $this->db->query_first($sql);	 	if(is_array($result) &&!empty($result) && count($result)>0)	 	{	 		$result['updown'] = unserialize($result['updown']);			$result['mood'] = unserialize($result['mood']);			return $result;	 	}	 	else {			return $result;	 	}	}		//判断是否存在 $id	public function extis_webapp($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp WHERE active = 1 and content_id = ' . $id;		$result = array();		$result = $this->db->query_first($sql);		return $result;	}		//判断是否存在 $id	public function extis_one_webapp($listid,$ip,$type)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp_list WHERE active = 1 and listid = ' . $listid.' and mark_name = "' . $type.'" and ip = "' . $ip.'" order by create_time desc limit 0,1 ';		$result = array();		$result = $this->db->query_first($sql);		return $result;	}		//新增webapp	public function create_list($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'webapp SET ' . implode(',', $fields);		$this->db->query($sql);		$data['id'] = $this->db->insert_id();		if ($data['id'])		{			return $data;		}		return false;	}		//新增webapp_list	public function create_one_list($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'webapp_list SET ' . implode(',', $fields);		$this->db->query($sql);		$data['id'] = $this->db->insert_id();		if ($data['id'])		{			return $data;		}		return false;	}					//根据关键字查询总数	public function count($data)	{		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'webapp WHERE active = 1';		return $this->db->query_first($sql);		}		//根据关键字查询总数	public function count_score($id = 0)	{		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'webapp_list WHERE 1 and listid='.$id.' and active = 1 and mark_name = "pingfen" ';		$result = $this->db->query_first($sql);		return $result['total'];	}			//配置信息基本信息读取 $id	public function detail($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'webapp WHERE active = 1 and id = ' . $id;		$result = array();		$result = $this->db->query_first($sql);		$result['updown'] = unserialize($result['updown']);		$result['mood'] = unserialize($result['mood']);		return $result;	}				//配置基本信息添加操作	public function create($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'webapp SET ' . implode(',', $fields);		$this->db->query($sql);		$data['id'] = $this->db->insert_id();		if ($data['id'])		{			return $data;		}		return false;	}			//更新配置基本信息	public function update($data, $id)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'webapp SET ' . implode(',', $fields) . ' WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		$this->db->query($sql);		return $data;	}		public function delete($id)	{		$sql = 'UPDATE ' . DB_PREFIX . 'webapp SET active = 0 WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		return $this->db->query($sql);	}			public function emptyData($table)	{		$sql = 'TRUNCATE ' . DB_PREFIX . $table;		return $this->db->query($sql);	}		public function get_condition($data)	{		$condition = '';				//查询的关键字		if(isset($data['key']))		{			$keyword = htmlspecialchars(trim(urldecode($data['key'])));			$condition .= " AND title LIKE '%" . $keyword . "%' ";		}		return $condition;	}		}?>