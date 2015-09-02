<?phpclass topicClass extends InitFrm{	public function __construct()	{		parent::__construct();	}		public function __destruct()	{		parent::__destruct();	}		/**	 * 根据条件获取话题信息	 * @param Int $offset	 * @param Int $count	 * @param Array $data	 */	public function show($offset, $count, $data = array())	{		if ($count != -1)		{			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		}				$sql = 'SELECT t.*, tr.content, tr.from_ip, tr.rid FROM ' . DB_PREFIX . 'topic t 		LEFT JOIN ' . DB_PREFIX . 'topic_reply tr ON t.topic_id = tr.topic_id WHERE tr.reply_user_id = 0';				//获取查询条件		$condition = $this->get_condition($data);		$sql = $sql . $condition . $data_limit;		$query = $this->db->query($sql);		$info = array();		$topic_ids = $space = "";		while ($row = $this->db->fetch_array($query))		{			$topic_ids .= $space . $row['topic_id'];			$space = ',';			$row['content'] = htmlspecialchars_decode($row['content']);			$info[] = $row;		}				if(!$topic_ids)		{			return false;		}		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE topic_id IN(" . $topic_ids . ")";		$q = $this->db->query($sql);		$material = array();		while($row = $this->db->fetch_array($q))		{			$row['img_info'] = unserialize(htmlspecialchars_decode($row['img_info']));			$material[$row['topic_id']][] = $row;		}				$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE source='topic' AND sid IN(" . $topic_ids . ")";		$q = $this->db->query($sql);		$video = array();		while($row = $this->db->fetch_array($q))		{			$video[$row['sid']] = $row;		}				foreach($info as $k => $v)		{			$info[$k]['topic_type'] = 'text';			if($video[$v['topic_id']])			{				$info[$k]['topic_type'] = 'video';				$info[$k]['data'] = $video[$v['topic_id']];			}			if($material[$v['topic_id']])			{				$info[$k]['topic_type'] = 'pic';				$info[$k]['data'] = $material[$v['topic_id']];			}		}		return $info;	}		/**	 * 根据ID获取话题信息	 * @param String $topic_ids	 * @param Int $state	 */	public function topic_by_id($topic_ids, $state = null)	{		if(empty($topic_ids))		{			return false;		}				$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE topic_id IN (" . $topic_ids . ")";		$q = $this->db->query($sql);		$material = array();		while($row = $this->db->fetch_array($q))		{			$row['img_info'] = unserialize(htmlspecialchars_decode($row['img_info']));			$material[$row['topic_id']][] = $row;		}				$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE source='topic' AND sid IN (" . $topic_ids . ")";		$q = $this->db->query($sql);		$video = array();		while($row = $this->db->fetch_array($q))		{			$video[$row['sid']] = $row;		}			$sql = 'SELECT t.*, tr.content, tr.from_ip, tr.rid FROM ' . DB_PREFIX . 'topic t 		LEFT JOIN ' . DB_PREFIX . 'topic_reply tr ON t.topic_id = tr.topic_id 		WHERE tr.reply_user_id = 0 AND t.topic_id in (' . $topic_ids . ')';		if (isset($state))		{			$sql .= ' AND t.state = ' . $state;		}		else		{			$sql .= ' AND t.state != 0';		}		$sql .= ' ORDER BY is_sticky DESC, is_essence DESC, topic_id DESC';				$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$row['topic_type'] = 'text';			$row['content'] = htmlspecialchars_decode($row['content']);			if($video[$row['topic_id']])			{				$row['topic_type'] = 'video';				$row['data'] = $video[$row['topic_id']];			}			if($material[$row['topic_id']])			{				$row['topic_type'] = 'pic';				$row['data'] = $material[$row['topic_id']];			}			$info[$row['topic_id']] = $row;		}		return $info;	}		/**	 * 根据条件获取话题数量	 * @param Array $data	 */	public function count($data = array())	{		$sql  = "SELECT COUNT(topic_id) AS total FROM " . DB_PREFIX . "topic t WHERE 1 ";		$condition = $this->get_condition($data);		$sql .= $condition;			return $this->db->query_first($sql);	}		/**	 * 获取单个话题的信息	 * @param Int $topic_id	 * @param Int $state	 */	public function detail($topic_id, $state = null)	{			$sql = 'SELECT t.*, tr.content, tr.from_ip, tr.rid FROM ' . DB_PREFIX . 'topic t 		LEFT JOIN ' . DB_PREFIX . 'topic_reply tr ON t.topic_id = tr.topic_id 		WHERE t.topic_id = ' . $topic_id . ' AND tr.reply_user_id = 0';		if (isset($state))		{			$sql .= ' AND t.state = ' . $state;		}		else		{			$sql .= ' AND t.state != 0';		}		$result = $this->db->query_first($sql);		if ($result)		{			$result['content'] = htmlspecialchars_decode($result['content']);		}		return $result;	}		/**	 * 获取某个话题的回复信息	 * @param Int $topic_id	 * @param Int $offset	 * @param Int $count	 */	public function reply_list($topic_id, $offset, $count)	{		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		$sql = 'SELECT * FROM ' . DB_PREFIX . 'topic_reply 		WHERE topic_id = ' . $topic_id . ' AND reply_user_id != 0 AND state = 1';		$sql .= ' ORDER BY rid ASC' . $data_limit;		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$row['content'] = htmlspecialchars_decode($row['content']);			$info[] = $row;		}		return $info;	}		/**	 * 根据ID获取回复数据	 * @param Int|String $reply_ids	 * @param Int $state	 */	public function reply_by_id($reply_ids, $state = null)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'topic_reply WHERE rid in (' . $reply_ids . ')';		if (isset($state))		{			$sql .= ' AND state = ' . $state;		}		else		{			$sql .= ' AND state != 0';		}		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$info[] = $row;		}		return $info;	}		/**	 * 获取某个话题的回复总数	 * @param Int $topic_id	 */	public function reply_count($topic_id)	{		$sql = 'SELECT COUNT(rid) AS total FROM ' . DB_PREFIX . 'topic_reply 		WHERE topic_id = ' . $topic_id . ' AND reply_user_id != 0 AND state = 1';		return $this->db->query_first($sql);	}		/**	 * 获取单个回复的信息	 * @param Int $rid	 */	public function detail_reply($rid)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'topic_reply WHERE rid = ' . $rid . ' AND state = 1';		return $this->db->query_first($sql);	}		/**	 * 获取当前最大楼层号	 * @param Int $topic_id	 */	public function get_max_floor($topic_id)	{		$sql = 'SELECT floor FROM ' . DB_PREFIX . 'topic_reply 		WHERE topic_id = ' . $topic_id . ' ORDER BY rid DESC LIMIT 1';		$result = $this->db->query_first($sql);		$max_floor = $result['floor'];		return ++$max_floor;	}		/**	 * 获取附件相关信息	 * @param String $where	 * @param Array $order	 * @param Array $limit	 */	public function get_material_info($where = '', $limit = array(0, 20), $order = array('create_time' => 'DESC'))	{			$sql = 'SELECT * FROM ' . DB_PREFIX . 'material WHERE state = 1';		if ($where) $sql .= ' AND ' . $where;		if ($order)		{			$sql .= ' ORDER BY ';			$delimiter = '';			foreach ($order as $k=>$v)			{				$sql .= $delimiter . $k . ' ' . $v;				$delimiter = ',';			}		}		if ($limit)		{			$sql .= ' LIMIT ' . $limit[0] . ', ' . $limit[1];		}		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$row['img_info'] = htmlspecialchars_decode($row['img_info']);			$row['img_info'] = unserialize($row['img_info']);			$info[] = $row;		}		return $info;	}		/**	 * 根据小组ID获取话题数据	 * @param String $ids	 */	public function get_topic_ids($ids, $flag = false)	{		$sql = 'SELECT topic_id FROM ' . DB_PREFIX . 'topic WHERE source_id in (' . $ids . ')';		if ($flag) $sql .= ' AND state != 0';		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$info[] = $row['topic_id'];		}		return $info;	}		public function get_reply_ids($ids)	{		$sql = 'SELECT rid FROM ' . DB_PREFIX . 'topic_reply 		WHERE topic_id in (' . $ids . ') AND state != 0';		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$info[] = $row['rid'];		}		return $info;	}		/**	 * 发布话题操作	 * @param Array $data	 */	public function create($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'topic SET ' . implode(',', $fields);		$this->db->query($sql);		$data['topic_id'] = $this->db->insert_id();		return $data;	}		/**	 * 发布话题关联数据	 * @param Array $data	 */	public function add_reply($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'topic_reply SET ' . implode(',', $fields);		$this->db->query($sql);		$data['rid'] = $this->db->insert_id();		return $data;	}		/**	 * 更新话题信息	 * @param Array $data	 * @param Int|String $topic_id	 */	public function update($data, $topic_id, $flag = false)	{		$fields = array();		foreach($data as $k=>$v)		{			if ($flag)			{				$v = $v > 0 ? '+' . $v : $v;				$fields[] = $k . '=' . $k . $v;			}			else			{				if (is_string($v))				{					$fields[] = $k . "='" . $v . "'";				}				elseif (is_int($v))				{					$fields[] = $k . '=' . $v;				}			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'topic SET ' . implode(',', $fields) . ' WHERE 1';		if (is_int($topic_id))		{			$sql .= ' AND topic_id = ' . $topic_id;		}		elseif (is_string($topic_id))		{			$sql .= ' AND topic_id in (' . $topic_id . ')';		}		return $this->db->query($sql);	}		/**	 * 更新回复和话题	 * @param Array $data	 * @param Int|String $rid	 * @param Int|String $topic_id	 */	public function update_reply($data, $rid, $topic_id = '')	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'topic_reply SET ' . implode(',', $fields) . ' WHERE 1';		if ($rid)		{			if (is_int($rid))			{				$sql .= ' AND rid = ' . $rid;			}			elseif (is_string($rid))			{				$sql .= ' AND rid in (' . $rid . ')';			}		}		if ($topic_id)		{			if (is_int($topic_id))			{				$sql .= ' AND topic_id = ' . $topic_id;			}			elseif (is_string($topic_id))			{				$sql .= ' AND topic_id in (' . $topic_id . ')';			}		}		return $this->db->query($sql);	}		/**	 * 添加附件	 * @param Array $img_info	 * @param Array $data	 */	public function add_material($img_info, $data)	{		if(is_array($img_info) && !empty($img_info))		{			$counter = 0;			foreach($img_info as $img)			{				$sql = 'INSERT INTO ' . DB_PREFIX . 'material (user_id, user_name, team_id, topic_id, reply_id, img_info, create_time) 				VALUES(' . $data['user_id'] . ",'" . $data['user_name'] . "'," . $data['team_id'] . ',' . $data['topic_id'] . ',' . $data['reply_id'] . ",'" . $img . "'," . TIMENOW . ')';				$result = $this->db->query($sql);				if ($result) ++$counter;			}			return $counter;		}		else		{			return false;		}	}		/**	 * 删除附件	 * @param String $ids	 */	public function del_img($topic_id, $ids)	{		$sql = 'SELECT * FROM ' . DB_PREFIX . 'material WHERE m_id in (' . $ids . ') AND topic_id = ' . $topic_id;		$query = $this->db->query($sql);		$info = array();		while ($row = $this->db->fetch_array($query))		{			$info[$row['topic_id']][] = $row['m_id'];		}		if (is_array($info) && $info)		{			foreach ($info as $k => $v)			{				$info[$k] = count($v);			}		}		else		{			return false;		}		$sql = 'DELETE FROM ' . DB_PREFIX . 'material WHERE m_id in (' . $ids . ')';		$result = $this->db->query($sql);		//更新话题下的附件数		if ($result)		{			foreach ($info as $k => $v)			{				$result = $this->update(array('material_num' => -$v), $k, true);			}		}		return $result;	}		/**	 * 视频数据录入	 * @param Array $data	 */	public function add_video($data)	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'INSERT INTO ' . DB_PREFIX . 'topic_video SET ' . implode(',', $fields);		return $this->db->query($sql);	}		/**	 * 查询条件	 * @param Array $data	 */	public function get_condition($data = array())	{		$condition = '';			//查询的关键字		if($data['key'])		{			$condition .= " AND t.subject LIKE '%" . $data['key'] . "%' ";		}			//查询帖子用户		if($data['user_name'])		{			$condition .= " AND t.creater_name = '" . $data['user_name'] . "' ";		}			//查询的起始时间		if($data['start_time'])		{			$condition .= " AND t.pub_time > " . $data['start_time'];		}			//查询的结束时间		if($data['end_time'])		{			$condition .= " AND t.pub_time < " . $data['end_time'];		}			//查询发布的时间		if(is_numeric($data['date_search']))		{			$today = strtotime(date('Y-m-d'));			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));			switch(intval($data['date_search']))			{				case 1://所有时间段					break;				case 2://昨天的数据					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));					$condition .= " AND t.pub_time > '".$yesterday."' AND t.pub_time < '".$today."'";					break;				case 3://今天的数据					$condition .= " AND t.pub_time > '".$today."' AND t.pub_time < '".$tomorrow."'";					break;				case 4://最近3天					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));					$condition .= " AND t.pub_time > '".$last_threeday."' AND t.pub_time < '".$tomorrow."'";					break;				case 5://最近7天					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));					$condition .= " AND t.pub_time > '".$last_sevenday."' AND t.pub_time < '".$tomorrow."'";					break;				default://所有时间段					break;			}		}			//查询话题的状态		if(is_numeric($data['state']))		{				switch(intval($data['state']))			{				case 1: //所有状态					$condition .=" AND t.state != 0";				break;				case 2: //正常					$condition .=" AND t.state = 1";				break;				case 3: //已屏蔽					$condition .=" AND t.state = -1";				break;				case 4: //已关闭					$condition .=" AND t.state = 2";				break;				case 5: //已删除					$condition .=" AND t.state = 0";				break;				default:					$condition .=" AND t.state != 0";				break;			}		}			//查询小组下的讨论区		if(is_numeric($data['team_id']))		{			$condition .= " AND t.source_id = " . intval($data['team_id']);		}			//查询图片贴，视频贴		if(is_numeric($data['thread_img']))		{			switch(intval($data['thread_img']))			{				case 1://所有帖子					break;				case 2://图片贴					$condition .=" AND t.contain_img=1";					break;				case 3://视频贴					$condition .=" AND t.contain_media=1";					break;				default:					break;			}		}				$orderby = ' ORDER BY ';		if ($data['order'] && is_array($data['order']))		{			$order = '';			foreach ($data['order'] as $k => $v)			{				$order .= ',t.' . $k . ' ' . strtoupper($v);			}			$order = substr($order, 1);		}		else		{			$order = 't.pub_time DESC';		}		$orderby .= $order;				return $condition . $orderby;	}		//逻辑删除话题附件	public function update_topic_material($data, $mid, $topic_id = '')	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'material SET ' . implode(',', $fields) . ' WHERE 1';		if ($mid)		{			if (is_int($mid))			{				$sql .= ' AND m_id = ' . $mid;			}			elseif (is_string($mid))			{				$sql .= ' AND m_id in (' . $mid . ')';			}		}		if ($topic_id)		{			if (is_int($topic_id))			{				$sql .= ' AND topic_id = ' . $topic_id;			}			elseif (is_string($topic_id))			{				$sql .= ' AND topic_id in (' . $topic_id . ')';			}		}		return $this->db->query($sql);	}		//逻辑删除话题视频	public function update_topic_video($data, $id, $topic_id = '')	{		$fields = array();		foreach($data as $k=>$v)		{			if (is_string($v))			{				$fields[] = $k . "='" . $v . "'";			}			elseif (is_int($v))			{				$fields[] = $k . '=' . $v;			}		}		$sql = 'UPDATE ' . DB_PREFIX . 'video SET ' . implode(',', $fields) . ' WHERE 1';		if ($id)		{			if (is_int($id))			{				$sql .= ' AND id = ' . $id;			}			elseif (is_string($id))			{				$sql .= ' AND id in (' . $id . ')';			}		}		if ($topic_id)		{			if (is_int($topic_id))			{				$sql .= ' AND source = "topic" AND sid = ' . $topic_id;			}			elseif (is_string($topic_id))			{				$sql .= ' AND source = "topic" AND sid in (' . $topic_id . ')';			}		}		return $this->db->query($sql);	}		//--------------------------------------------------------------------------		/**	 * 删除回复	 * @param String $ids	 */	public function del_reply($ids)	{		$sql = 'DELETE FROM ' . DB_PREFIX . 'topic_reply WHERE topic_id in (' . $ids . ')';		return $this->db->query($sql);	}		/**	 * 删除附件	 * @param String $ids	 */	public function del_material($ids)	{		$sql = 'DELETE FROM ' . DB_PREFIX . 'material WHERE topic_id in (' . $ids . ')';		return $this->db->query($sql);	}		/**	 * 删除视频	 * @param String $ids	 */	public function del_video($ids)	{		$sql = 'DELETE FROM ' . DB_PREFIX . 'video WHERE source = "topic" AND sid in (' . $ids . ')';		return $this->db->query($sql);	}		/**	 * 删除话题	 * @param String $ids	 */	public function del_topic($ids)	{		$sql = 'DELETE FROM ' . DB_PREFIX . 'topic WHERE topic_id in (' . $ids . ')';		return $this->db->query($sql);	}}