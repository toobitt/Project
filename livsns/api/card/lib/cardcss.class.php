<?phpclass cardcssClass extends InitFrm{	public function __construct()	{		parent::__construct();	}		public function __destruct()	{		parent::__destruct();	}		public function show($offset, $count)	{		if ($count != -1)		{			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		}		$sql = 'SELECT * FROM ' . DB_PREFIX . 'card_content_css WHERE active = 1 ';				$paixu = " order by order_id asc ";		$sql .= $paixu;		if ($data_limit) $sql .= $data_limit;		$query = $this->db->query($sql);		$info = array();		while ($rows = $this->db->fetch_array($query))		{			$info[] = $rows;		}		return $info;	}		//信息读取 $id	public function detail($id)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'card_content_css WHERE active = 1 and id = ' . $id;		return $this->db->query_first($sql);	}		//基本信息添加操作	public function create($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'card_content_css SET ' . implode(',', $fields);		$this->db->query($sql);		$data['id'] = $this->db->insert_id();		if ($data['id'])		{			return $data;		}		return false;	}			//更新基本信息	public function update($data, $id)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'card_content_css SET ' . implode(',', $fields) . ' WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		$this->db->query($sql);		return $data;	}		public function delete($id)	{		$sql = 'UPDATE ' . DB_PREFIX . 'card_content_css SET active = 0 WHERE 1';		if (is_int($id))		{			$sql .= ' AND id = ' . $id;		}		elseif (is_string($id))		{			$sql .= ' AND id in (' . $id . ')';		}		return $this->db->query($sql);	}		public function emptyData($table)	{		$sql = 'TRUNCATE ' . DB_PREFIX . $table;		return $this->db->query($sql);	}		public function get_condition()	{	}}?>